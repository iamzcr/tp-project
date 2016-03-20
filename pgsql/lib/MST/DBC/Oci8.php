<?php
/**
 * Class MST_DBC_Oci8
 * Oracle 数据库驱动底层封装
 * @package MST_DBC
 */
class MST_DBC_Oci8 implements MST_IDBC {

	const
		DT_FORMAT   = 'yyyy-mm-dd hh24:mi:ss',
		SEQ_FROMAT	= 'SEQ_:table_:column';

	private static
		// 默认的数据库连接格式，用于补充config文件中缺失的字段
		$_defaultConfig = array(
			'host'		=> 'localhost',
			'port'		=> 1521,
			'user'		=> 'SCOTT',
			'password'	=> 'TIGER',
			'dbname'	=> null,
			'prefix'	=> null,
			'charset'	=> 'utf8',
			'persistent'=> true,
		),
		$_pdoOptions = array( /* 标准化所有DB的默认行为 */
			// 限制全部字段强制转换为小写
			PDO::ATTR_CASE				=> PDO::CASE_LOWER,
			// 空字符串转换为php的null值
			PDO::ATTR_ORACLE_NULLS		=> PDO::NULL_EMPTY_STRING,
			// 数字内容转换为(true:string|false:number)类型
			PDO::ATTR_STRINGIFY_FETCHES	=> false,
		);

	private
		$_defaultFetchMode = PDO::FETCH_ASSOC,
		$_config = null,
		$_connector = null,
		$_statement = null,
		$_isTakeOverDisconnect = false,
		$_lastHash = null,
		$_querySql = null;

	// 这里的config是引用变量
	public function __construct(& $config) {
		$this->connect($config);
	}

	public function connect(& $config) {
		$config = array_merge(self::$_defaultConfig, $config);
		try {
			$dsn = '//' . $config['host'] . ':' . $config['prot'] . '/' . $config['dbname'];
			if ($config['persistent'])
				$this->_connector = oci_pconnect($config['user'], $config['password'], $dsn, $config['charset']);
			else
				$this->_connector = oci_connect($config['user'], $config['password'], $dsn, $config['charset']);
			$this->_config = $config;
			oci_internal_debug(MST_Core::inPro ? 0 : 1);
			// 定义PDO的报错的机制
			switch (MST_Core::getEnv()) {
				case MST_Core::IN_DEV  : /* DEV模式，任意出错直接中断 */
					$errorMode = PDO::ERRMODE_EXCEPTION;
					break;
				case MST_Core::IN_TEST : /* TEST模式，任意出错都会waring */
					$errorMode = PDO::ERRMODE_WARNING;
					break;
				default : /* 其他模式下，采用静默模式，不报错 */
					$errorMode = PDO::ERRMODE_SILENT;
			}
			$this->_connector->setAttribute(PDO::ATTR_ERRMODE, $errorMode);
			$this->_connector->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
			$this->_connector->query("alter session set nls_date_format = '".self::DT_FORMAT."'");
		}
		catch (Exception $error) {
			MST_Core::error(304, $error->getMessage(), $config['adapter']);
		}
	}

	public function disconnect() {
		if (!empty($this->_connector)) $this->_connector = null;
		if (!empty($this->_statement)) $this->_statement = null;
		$this->_querySql = null;
		$this->_lastHash = null;
	}

	public function __destruct() {
		if (!$this->_isTakeOverDisconnect)
			$this->disconnect();
	}

	public function getStatement() {
		return $this->_statement;
	}

	public function getConnector() {
		return $this->_connector;
	}

	public function getFecthMode($mode = MST_DBC::FETCH_ASSOC) {
		switch ($mode) {
			case MST_DBC::FETCH_BOTH  : $mode = PDO::FETCH_BOTH; break;
			case MST_DBC::FETCH_NUM   : $mode = PDO::FETCH_NUM;  break;
			default                   : $mode = PDO::FETCH_ASSOC;
		}
		return $mode;
	}

	public function lastSql(& $index = 0) {
		if (is_numeric($index)) {
			$length = count($this->_querySql);
			if ($index < 0) {
				$pos = $length - 3 - $index;
			}
			else {
				$pos = $length - 1;
			}
			$sqls = array_values($this->_querySql);
			$sql = isset($sqls[$pos]) ? $sqls[$pos] : null;
			return $sql;
		}
		elseif (is_string($index))
			return isset($this->_querySql[$index]) ? $this->_querySql[$index] : null;
	}

	public function lastInsertId($table, $column) {
		$this->execute('SELECT '.$this->getSeqName($table, $column).'.CURRVAL FROM dual');
		$result = $this->_statement->fetch(PDO::FETCH_NUM);
		return (int)$result[0];
	}

	public function getSeqName($table, $column) {
		$seqName = str_ireplace(array(':table', ':column'), array($table, $column), self::SEQ_FROMAT);
		$seqName = strtoupper($seqName);
		return $seqName;
	}

	public function & query($sql, $params = null) {
		if (!empty($params) && !is_array($params))
			$params = array($params);
		$this->_lastHash = $this->mkSqlHash($sql, $params);
		$this->_querySql[$this->_lastHash] = array($sql, $params);
		try {
			$this->_statement = $this->_connector->prepare((string)$sql);
			$this->_statement->execute($params);
			return $this->_statement;
		}
		catch (Exception $e) {
			MST_Core::error(310, $e->getMEssage(), $sql, var_export($params, 1));
		}
	}

	public function execute($sql, $params = null) {
		$statement = $this->query($sql, $params);
		if ($statement === false)
			return 0;
		else
			return $statement->rowCount();
	}

	public function showTables() {
		$this->execute('SELECT table_name FROM user_tables');
		$tables = $this->_statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($tables as & $table) {
			$table = $table['table_name'];
		}
		return $tables;
	}

	public function select($conditions, $params = null) {
		$fetchMode = empty($conditions['fetchMode'])
					 ? $this->_defaultFetchMode
					 : $this->getFecthMode($conditions['fetchMode']);
		if (is_array($conditions))
			$this->mkSelectSql($conditions, $sql, $params);
		else
			$sql = $conditions;
		$statement = $this->query($sql, $params);
		if (empty($conditions[0])) $conditions[0] = MST_DBO::FIRST;
		switch ($conditions[0]) {
			case MST_DBO::ALL :
				return $statement->fetchAll($fetchMode);
				break;
			default :
				return $statement->fetch($fetchMode);
				break;
		}
	}

	// 在数据操作的底层，不去管你是否插入的为一个空白的数据
	// 空白数据的控制在Model层面控制
	public function insert($table, array $data, $primaryKey = null) {
		foreach ($data as $key => $val) {
			$keys[] = $key;
			$placeholds[] = '?';
			$params[] = $val;
		}
		if (empty($primaryKey)) {
			$sql = 'INSERT INTO ' . (string)$table .
				   ' (' . implode(', ', $keys) . ')' .
				   ' VALUES (' . implode(', ', $placeholds) . ')';
		}
		else {
			$sql = 'INSERT INTO ' . (string)$table .
				   ' (' . (string)$primaryKey . ', ' . implode(', ', $keys) . ')' .
				   ' VALUES (' . $this->getSeqName($table, $primaryKey) . '.NEXTVAL, ' . implode(', ', $placeholds) . ')';
		}
		return $this->execute($sql, $params);
	}

	// 指定条件更新，$target是不允许空的，否则返回更新了0条记录
	// 并且，假如你尝试update一个空数组，那直接结果就是0
	public function update($table, array $target, array $data) {
		if (empty($target) || empty($data)) return 0;
		foreach ($data as $key => $val) {
			$fileds[] = "{$key} = ?";
			$params[] = $val;
		}
		$sql = 'UPDATE ' . (string)$table . ' SET ' . implode(', ', $fileds);
		if (!empty($target['where']))
			$this->mkWhereSql($target['where'], $sql, $params);
		if (!empty($target['in']))
			$this->mkWhereSql($target['in'], $sql, $params);
		return $this->execute($sql, $params);
	}

	public function updateAll($table, array $data) {
		if (empty($data)) return 0;
		foreach ($params['data'] as $key => $val) {
			$fileds[] = "{$key} = ?";
			$params[] = $val;
		}
		$sql .= 'UPDATE ' . (string)$table . ' SET ' . implode(', ', $fileds);
		return $this->execute($sql, $params);
	}

	// 删除时不允许删除$target为空的
	// 当执行删除空对象的操作时，则返回没执行成功
	public function delete($table, array $target) {
		if (empty($target)) return 0;
		$sql = 'DELETE FROM ' . (string)$table;
		$params = null;
		if (!empty($target['where']))
			$this->mkWhereSql($target['where'], $sql, $params);
		if (!empty($target['in']))
			$this->mkWhereSql($target['in'], $sql, $params);
		return $this->execute($sql, $params);
	}

	public function deleteAll($table) {
		return $this->execute('DELETE FROM ' . (string)$table);
	}

	public function truncate($table) {
		return $this->execute('TRUNCATE TABLE ' . (string)$table);
	}

	public function getDtFormat() {
		return self::DT_FORMAT;
	}

	public function startTransaction() {
		$this->_connector->beginTransaction();
	}

	public function commit() {
		$this->_connector->commit();
	}

	public function rollBack() {
		$this->_connector->rollBack();
	}
	
	public function isAutoCommit() {
		return $this->_isAutoCommit;
	}
	
	public function takeOverDisconnect() {
		$this->_isTakeOverDisconnect = true;
	}

	public function fetch($style = MST_DBC::FETCH_ASSOC) {
		if ($this->_statement != null)
			return $this->_statement->fetch($style);
		return null;
	}

	public function fetchAll($style = MST_DBC::FETCH_ASSOC) {
		if ($this->_statement != null)
			return $this->_statement->fetchAll($style);
		return null;
	}

	public function quote($val) {
		return $this->_connector->quote($val);
	}

	private function mkSqlHash($sql, $params = null) {
		if ($params !== null && is_array($params))
			$sql .= '\t' . implode('\t', $params);
		return md5($sql);
	}

	public function mkSelectSql($cd, & $sql = null, & $params = null) {
		if (empty($cd['select']) || empty($cd['from']))
			MST_Core::error(311, $sql, var_export($cd, 1), var_export($params, 1));
		$sql = 'SELECT ' . $cd['select'] . ' FROM ' . $cd['from'];
		$limit = empty($cd['limit']) ? 0 : intval($cd['limit']);
		$offset = empty($cd['offset']) ? 0 : intval($cd['offset']);
		
		if (!empty($cd['join']) && is_string($cd['join']))
			$sql .= $cd['join'];
		if (!empty($cd['where'])) {
			$this->mkWhereSql($cd['where'], $sql, $params);
			//if ($offset <= 0 && $limit > 0)
			//	$sql .= ' AND ROWNUM < ' . ($limit + 1);
		}
		//elseif ($offset <= 0 && $limit > 0)
		//	$sql .= ' WHERE ROWNUM < ' . ($limit + 1);
		if (!empty($cd['in']))
			$this->mkInSql($cd['in'], $sql, $params);
		if (!empty($cd['group']) && is_string($cd['group'])) {
			$sql .= ' GROUP BY ' . $cd['group'];
			if (!empty($cd['having'])) {
				$this->mkBySql($cd['having'], $tempSql, $params);
				$sql .= ' HAVING ' . $tempSql;
			}
		}
		if (!empty($cd['order']) && is_string($cd['order']))
			$sql .= ' ORDER BY ' . $cd['order'];

		if ($offset <= 0 && $limit > 0) {
			$sql = 'SELECT * FROM (SELECT a.*, rownum rnum FROM (' .
				$sql .
			') a WHERE rownum < ' . ($limit + 1 + $offset) . ') WHERE rnum > ' . ($offset);
		}
		elseif ($offset > 0) {
			$sql = 'SELECT * FROM (SELECT a.*, rownum rnum FROM (' .
				$sql .
			') a WHERE rownum < ' . ($limit + 1 + $offset) . ') WHERE rnum > ' . ($offset);
		}
	}

	private function mkWhereSql($where, & $sql = null, & $params = null) {
		$tempSql = null;
		if (is_string($where))
			$tempSql = $where;
		elseif (is_array($where)) {
			if (!empty($where[0]) && is_string($where[0])) {
				$tempSql = array_shift($where);
				if (!empty($where) && is_array($where)) {
					foreach ($where as $val)
						$params[] = $val;
				}
			}
			else {
				$this->mkBySql($where, $tempSql, $params);
			}
		}
		if ($tempSql !== null) {
			$wherePos = strripos($sql, 'where');
			if ($wherePos === false)
				$sql .= ' WHERE ';
			else {
				if ($wherePos != strlen(trim($sql)) - 5)
					$sql .= ' WHERE  ';
			}
			$sql .= $tempSql;
		}
	}

	private function mkInSql($in, & $sql = null, & $params = array()) {
		if (empty($in)) return;
		if (is_array($in)) {
			$genSql = null;
			foreach ($in as $key => $val) {
				if (empty($val) && $val != 0) continue;
				if (is_string($val) && strpos($val, ',') !== false)
					$val = explode(',', $val);
				if (is_string($val) || is_numeric($val)) {
					$params[] = $val;
					$genSql .= (empty($genSql) ? null : ' AND ') . $key . ' = ?';
				}
				elseif (is_array($val)) {
					$tempSql = null;
					foreach ($val as &$v) {
						$v = trim($v);
						if (!$v) continue;
						$tempSql .= !$tempSql ? '?' : ',?';
						$params[] = $v;
					}
					$genSql .= (empty($genSql) ? null : ' AND ') . $key . ' IN (' . $tempSql . ')';
				}
			}
			$wherePos = strripos($sql, 'where');
			if ($wherePos === false)
				$sql .= ' WHERE ';
			else {
				if ($wherePos != strlen(trim($sql)) - 5)
					$sql .= ' WHERE  ';
				else
					$sql .= ' AND ';
			}
			$sql .= $genSql;
		}
	}

	private function mkBySql($by, & $sql = null, & $params = array()) {
		if (empty($by)) return;
		$sql = empty($sql) ? null : ' AND ';
		if (is_array($by)) {
			foreach ($by as $key => $val) {
				$operator = '=';
				if (is_array($val)) {
					if (!empty($val[1])) {
						$operator = $val[0];
						$val = $val[1];
					}
					else
						$val = $val[0];
				}
				$sql .= (empty($sql) ? null : ' AND ') .
					$key . ' ' . $operator . ' ?';
				$params[] = $val;
			}
		}
	}
}
