<?php
/**
 * DataBase Common Connector
 * 数据库共用连接器，连接驱动以如下的方式存放：
 * DBC/PdoOci.php
 * DBC/PdoMySQL.php
 * DBC/MySQLi.php
 * 连接驱动必须继承自DBC，详情以某个驱动为例
 *
 * 调用一个数据库的连接实例如下：
 * <code>
 * MST_DBC::connect($remote);
 * # $remote对应config中配置
 * </code>
 *
 * @author Janpoem
 */

/**
 * Class MST_IDBC
 * 底层数据驱动接口
 * @package MST
 * @api
 */
interface MST_IDBC {

    /**
     * 连接到具体的数据库
     * @param $config 数据库连接配置
     * @return MST_IDBC
     */
    public function connect(& $config);

    /**
     * 断开数据库连接
     * @return void
     */
    public function disconnect();

    /**
     * 获取底层PDO的Statement对象
     * @return mixed
     */
    public function getStatement();

    /**
     * 获得底层PDO对象
     * @return PDO
     */
    public function getConnector();

    /**
     * 设置底层PDO查询后填充数据的方式
     * 可能值： MST_DBC::FETCH_BOTH|MST_DBC::FETCH_NUM|MST_DBC::FETCH_ASSOC
     * @param int $mode
     * @return mixed
     */
    public function getFecthMode($mode = MST_DBC::FETCH_ASSOC);

    /**
     * 获取SQL查询记录
     * @param int $index 记录的索引值
     * @return mixed
     */
    public function lastSql(& $index = 0);

    /**
     * 获取最近一次插入查询的索引值，常见的是主键值
     * @param $table 查询的表名
     * @param $column 查询的列值
     * @return mixed
     */
    public function lastInsertId($table, $column);

    /**
     * 执行sql查询返回PDO Statement对象
     * PDO标准传参方式
     * @param $sql sql语句
     * @param array|null $params 参数数组
     * @return PDOStatement
     */
    public function & query($sql, $params = null);

    /**
     * 执行SQL查询，返回受影响的行数
     * PDO标准传参方式
     * @param $sql sql语句
     * @param array|null $params 参数数组
     * @return number
     */
    public function execute($sql, $params = null);

    /**
     * 根据查询参数，返回查询结果数组
     * @param $conditions 查询参数
     * @see http://code.mixmedia.com/mixmvc3-1-model/
     * @param array|null $params where条件的传参数组
     * @return array
     */
    public function select($conditions, $params = null);

    /**
     * 插入单条数据结构到数据库
     * @param $table 操作的数据表
     * @param array $data 待插入的数据结构
     * @return number|bool 失败时返回0|false，插入成功返回新数据的主键值
     */
    public function insert($table, array $data);

    /**
     * 更新单条数据记录到数据库
     * @param $table 操作的数据表
     * @param array $target 更新的条件结构，可以是主键|where条件|in条件
     * @param array $data 待更新的数据
     * @return number 受影响的行数
     */
    public function update($table, array $target, array $data);

    /**
     * 更新所有数据
     * @param $table
     * @param array $data
     * @return mixed
     * @deprecated
     */
    public function updateAll($table, array $data);

    /**
     * 删除数据
     * @param $table 操作的数据表
     * @param array $target 更新的条件结构，可以是主键|where条件|in条件
     * @return number 受影响的行数
     */
    public function delete($table, array $target);

    /**
     * 删除数据表下的所有记录
     * 不会重置自增种子
     * @param $table 操作的数据表
     * @return number 受影响的行数
     */
    public function deleteAll($table);

    /**
     * 清空数据表
     * 会重置自增种子
     * @param $table 操作的数据表
     * @return number 受影响的行数
     */
    public function truncate($table);

    /**
     * 显示所有的数据表
     * @return mixed
     * @todo 还木有实现
     */
    public function showTables();

    /**
     * 获取日期的展示格式
     * 一般系统中使用unix timestamp保存时间
     * @return mixed
     */
    public function getDtFormat();

    /**
     * 开始事务
     * @return mixed
     */
    public function startTransaction();

    /**
     * 提交事务
     * 事务中的所有修改将会提交并保存
     * @return mixed
     */
    public function commit();

    /**
     * 回滚事务
     * 事务中的所有修改将会回滚
     * @return mixed
     */
    public function rollBack();

    /**
     *
     * @return mixed
     */
    public function takeOverDisconnect();

    /**
     * 填充单条查询数据
     * @param int $style 填充模式，可能值：MST_DBC::FETCH_BOTH|MST_DBC::FETCH_NUM|MST_DBC::FETCH_ASSOC
     * @return array|null
     */
    public function fetch($style = MST_DBC::FETCH_ASSOC);

    /**
     * 填充所有查询数据
     * @param int $style 填充模式，可能值：MST_DBC::FETCH_BOTH|MST_DBC::FETCH_NUM|MST_DBC::FETCH_ASSOC
     * @return array|null
     */
    public function fetchAll($style = MST_DBC::FETCH_ASSOC);

    /**
     * 转义SQL查询安全字符串
     * @param $val
     * @return mixed
     */
    public function quote($val);

    /**
     * 查询是否自动提交状态
     * @return bool
     */
    public function isAutoCommit();
}

/**
 * Class MST_DBC
 * 数据库操作逻辑类
 * @package MST
 * @api
 */
abstract class MST_DBC {


    /**
     * 数据库连接配置的集合键值 本地数据库
     */
    const LOCAL		= 'default';
    /**
     * 底层PDO驱动 MYSQL
     */
    const PDO_MYSQL	= 'mysql';
    /**
     * 底层PDO驱动 ORACLE
     */
    const PDO_OCI		= 'oracle';
    /**
     * 底层PDO驱动 PGSQL
     */
    const PDO_PGSQL   = 'pgsql';
    /**
     * 底层数据库驱动 OCI8
     */
    const OCI8		= 'oci8';
	const CLOB		= 'CLOB';
	const BLOB		= 'BLOB';
	const CREATE		= 'create';
	const UPDATE		= 'update';
	const DELETE		= 'delete';
    /**
     * 填充模式 字符串索引
     */
    const FETCH_ASSOC	= 2;
    /**
     * 填充模式 数字索引
     */
    const FETCH_NUM	= 3;
    /**
     * 填充模式 字符串索引&数字索引
     */
    const FETCH_BOTH	= 4;

	private static
		$_dbConfigKey = 'database',
		$_dbConfig = array(),
		$_adapters = array(
			self::PDO_MYSQL		=> 'PdoMySQL',
			self::PDO_OCI		=> 'PdoOci',
			self::OCI8			=> 'Oci8',
			self::PDO_PGSQL		=> 'PdoPgSQL',
		),
		$_importAdapters = array(),
		$_register = array();

	protected static
		$_querySql = array(),
		$_lastHash = null;

    /**
     * 连接到具体的底层数据驱动
     * @param null|string $remote 数据库连接配置的集合键值
     * @return MST_IDBC
     */
    final static public function & connect($remote = null) {
		if ($remote == null) $remote = self::LOCAL;
		if (!isset(self::$_register[$remote])) {
			$config = MST_Core::getConfig(self::$_dbConfigKey, $remote);
			if (empty($config))
				MST_Core::error(301, $remote);
			if (empty($config['adapter'])
			 || !isset(self::$_adapters[$config['adapter']]))
				MST_Core::error(302, $remote);
			$adapter = self::$_adapters[$config['adapter']];
			$adapterClass = __CLASS__ . '_' . $adapter;
			if (!isset(self::$_importAdapters[$adapter])) {
				if (!MST_Core::import("MST/DBC/{$adapter}", MST_Core::P_LIB)
				 || !class_exists($adapterClass))
					MST_Core::error(302, "MST/DBC/$adapter");
				self::$_importAdapters[$adapter] = 1;
			}
			self::$_register[$remote] = new $adapterClass($config);
		}
		return self::$_register[$remote];
	}

    /**
     * 断开数据库连接
     * @param null $remote 数据库连接配置的集合键值
     */
    final static public function disconnect($remote = null) {
		if ($remote == null) {
			foreach (self::$_register as $conn) {
				if (!empty($conn)) $conn->disconnect();
			}
		}
		else {
			self::connect($remote)->disconnect();
		}
	}

    /**
     * 获取具体的数据库连接配置
     * @param $key 具体配置的键值
     * @param string $remote 数据库连接配置的集合键值
     * @return null
     */
    final static public function getConfig($key, $remote = self::LOCAL) {
		if (empty(self::$_dbConfig[$remote]))
			self::$_dbConfig[$remote] = MST_Core::getConfig(self::$_dbConfigKey, $remote);
		if (!empty(self::$_dbConfig[$remote][$key]))
			return self::$_dbConfig[$remote][$key];
		return null;
	}

    /**
     * 添加底层数据库驱动
     * @param $key 驱动标识
     * @param $val
     */
    final static public function addAdapter($key, $val) {
		if (!isset(self::$_adapters[$key]))
			self::$_adapters[$key] = $val;
	}

    /**
     * 获取最近一次查询的hash值
     * @return null
     */
    final static public function getLastSqlHash() {
		return self::$_lastHash;
	}

    /**
     * 启动数据库事务
     * @param string $remote 数据库连接配置的集合键值
     * @return mixed
     */
    final static public function startTransaction($remote = self::LOCAL) {
		return self::connect($remote)->startTransaction();
	}

    /**
     * 数据库事务提交
     * @param string $remote 数据库连接配置的集合键值
     * @return mixed
     */
    final static public function commit($remote = self::LOCAL) {
		return self::connect($remote)->commit();
	}

    /**
     * 数据库事务回滚
     * @param string $remote 数据库连接配置的集合键值
     * @return mixed
     */
    final static public function rollBack($remote = self::LOCAL) {
		return self::connect($remote)->rollBack();
	}

    /**
     * 接管某个连接实例的
     * @param string $remote 数据库连接配置的集合键值
     * @return mixed
     */
    final static public function handleDisconnect($remote = self::LOCAL) {
		return self::connect($remote)->takeOverDisconnect();
	}

}
