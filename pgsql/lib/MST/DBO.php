<?php

if (!defined('IN_MST_CORE'))
	exit('MST_ActionController Can\'t be include single!');

if (!defined(MST_Core::LITE_MODE)) {
	MST_Core::import(array(
		'MST/DBC',
		'MST/DataSet',
		'MST/DBO/DataSet',
		'MST/DBO/Table',
		'MST/DBO/Error',
		'MST/DBO/Validator'
	), MST_Core::P_LIB);
}

global $data_cache;
if (!$data_cache) {
    $data_cache = array();
}
/**
 * 数据库基础通用数据库交互对象，所有的数据Model都继承自此类
 * @see  http://code.mixmedia.com/mixmvc3-1-model/
 * @package MST
 */
abstract class MST_DBO extends MST_DBO_DataSet implements IMST_DBO_Error {

    /**
     * find查询标识，表示返回单行数据
     */
    const FIRST = 'first';
    /**
     * find查询标识，表示返回多行数据
     */
    const ALL = 'all';
	const ONE = 'one';
    /**
     * 数据集成关系标识 从属
     * @todo 未实现
     */
    const BELONGS_TO = 0;
    /**
     * 数据包含关系标识 一对一
     * @todo 未实现
     */
    const HAS_ONE = 1;
    /**
     * 数据包含关系标识 一对多
     * @todo 未实现
     */
    const HAS_MANY = 2;
    /**
     * 数据包含关系标识 多对多
     * @todo 未实现
     */
    const MANY_TO_MANY = 3;


    // DB Model的特殊声明
    /**
     * @var null 当前modelclass name
     */
    protected static $name = null;
    /**
     * @var MST_DBO_Table 对应的数据表名
     */
    protected static $table = null;
    /**
     * @var null 数据库前缀
     */
    protected static $prefix = null;
    /**
     * @var string 默认的数据库链接配置快标识
     */
    protected static $remote = MST_DBC::LOCAL;
    /**
     * @var string 默认的主键字段名
     */
    protected static $primaryKey = 'id';
    /**
     * @var string 默认的字段查询字符串
     */
    protected static $defaultSelect = '*';
    /**
     * @var array
     */
    protected static $defaultFind = array();
    /**
     * @var array
     */
    protected static $findAlias = array();
    /**
     * @var array 数据Model的字段描述
     */
    protected static $columns = array();
    /**
     * @var array 数据Model的字段结构
     */
    protected static $struct = null;
    /**
     * @var array
     * @todo 未实现功能
     */
    protected static $hasOne = array();
    /**
     * @var array
     * @todo 未实现功能
     */
    protected static $hasMany = array();
    /***
     * @var array
     * @todo 未实现功能
     */
    protected static $belongsTo = array();
    protected static $hasAndBelongsToMany = array();
    /**
     * @var bool 自动递增标识
     */
    protected static $isAutoInc = true;
    /**
     * @var bool 是否自动提交事务
     */
    protected static $isAutoCommit = true;
    /**
     * @var array 二进制数据字段
     */
    protected static $LOBColumns = array();
    /**
     * @var string 默认返回的数据集合类型
     */
    protected static $dataSetClass = 'MST_DBO_DataSet';


    /**
     * @var null 主键
     */
    protected $_pk = null;
    /**
     * @var bool 是否单行数据
     */
    protected $_asRow = true;
    /**
     * @var array 源数据映射
     */
    protected $_shadow = null;
    /**
     * @var array model的数据结构
     */
    protected $_struct = null;
    /**
     * @var array
     * @todo 未实现功能
     */
    protected $_hasOne = array();
    /**
     * @var array
     */
    protected $_hasMany = array();
    /**
     * @var array
     * @todo 未实现功能
     */
    protected $_belongsTo = array();
    /**
     * @var array
     * @todo 未实现功能
     */
    protected $_hasAndBelongsToMany = array();
    /**
     * @var MST_DBO_Validator 内部数据验证器
     */
    protected $_validator = null;
    /**
     * @var bool 无更新数据标识
     */
    protected $_noUpdateColumn = false;

    /**
     * @var bool 是否只更新改变数据
     */
    public $isUpdateOnChange = true;

    /**
     * 数据Model预处理函数，只在框架流程中会被调用，用于填充静态变量及缓存数据
     */
    static public function __init() {
        global $data_cache;
		$model = get_called_class();
		// 强行设置Model的Name
		$model::setStatic($model, 'name', $model);
        $data_cache['tables'][$model] = new MST_DBO_Table($model, static::$table);
		static::$table = &$data_cache['tables'][$model];
		static::$table->setPrefix(static::$prefix !== false && static::$prefix == null ? MST_DBC::getConfig('prefix', static::$remote) : static::$prefix);
	}

    /**
     * 配置静态变量
     * @param $model 数据model类
     * @param $key 变量名称
     * @param $val 变量值
     */
    static protected function setStatic($model, $key, & $val) {
		$model::${$key} = &$val;
	}

    /**
     * 获取静态变量
     * @param $model 数据model类
     * @param $key 变量名称
     * @param null $var 变量值
     * @return mixed|null 如果设置成功侧返回具体的变量值
     */
    static public function getStatic($model, $key, & $var = null) {
		return isset($model::${$key}) ? $var = $model::${$key} : null;
	}

    /**
     * 判断是否存在静态变量值
     * @param $model 数据model类
     * @param $key 变量名称
     * @return bool
     */
    static public function hasStatic($model, $key) {
		return isset($model::${$key});
	}

    /**
     * 设置表结构
     * @param $column 字段值
     * @param $options 结构内容
     */
    final static public function setColumnOption($column, $options) {
		if (!isset(static::$columns[$column]))
			static::$columns[$column] = $options;
		else
			static::$columns[$column] = array_merge(static::$columns[$column], $options);
	}

    /**
     * 获取表结构值
     * @param $column
     * @param null $key 具体的字段值
     * @return string|null 为空时返回所有的结构
     */
    final static public function getColumnOption($column, $key = null) {
		if ($key != null)
			return isset(static::$columns[$column][$key]) ? static::$columns[$column][$key] : null;
		else
			return isset(static::$columns[$column]) ? static::$columns[$column] : null;
	}

    /**
     * 获取字段的描述名称[非数据库中的字段名]
     * @param $column 具体的字段值
     * @return mixed
     */
    static public function getColumnTitle($column) {
		return isset(static::$columns[$column]['title']) ? static::$columns[$column]['title'] : $column;
	}

    /**
     * 获取整个model的字段结构数据
     * @return array
     */
    final static public function getAllColumnOptions() {
		return static::$columns;
	}


    /**
     * 配置数据model默认返回的数据集合类型
     * @param $class
     */
    final static public function setDataSetClass($class) {
		if (class_exists($class))
			static::$dataSetClass = $class;
	}

    /**
     * find函数变体，直接返回array数据
     * @param array $args
     * @param null $params
     * @return array
     */
    final static public function findArray($args = array(), $params = null) {
		return self::find($args, $params, true);
	}

    /**
     * 构造查询结构
     * @param array $args
     * @param null $params
     * @param bool $isArray
     * @return mixed
     */
    static public function initFind(& $args = array(), & $params = null, & $isArray = false) {
		if (!is_array($args)) {
			switch (true) {
				case $args == null :
					return new static::$name();
					break;
				case isset(static::$findAlias[$args]) :
					$args = static::$findAlias[$args];
					break;
				case $args === self::ALL || $args === self::FIRST :
					if ($params == null)
						$args = array($args);
					else {
						array_unshift($params, $args);
						$args = $params;
					}
					break;
				case empty(static::$primaryKey) :
					$args = array(self::FIRST);
					break;
				default :
					$args = explode(',', $args);
					$args = array(
						count($args) == 1 ? self::FIRST : self::ALL,
						'in' => array(
							static::$primaryKey => $args,
						)
					);
					
			}
		}
	}

    /**
     * @param array $args 'first'|'all'|主键
     * @param array $params 查询条件格式：http://code.mixmedia.com/mixmvc3-1-model/
     * @param bool $isArray 是否返回数组而不是DataSet对象
     * @return array|MST_DataSet|MST_DBO
     */
    static public function find($args = array(), $params = null, $isArray = false) {
        global $data_cache;
		$pager = null;
		if (!is_array($args)) {
			switch (true) {
				case $args == null :
					return new static::$name();
					break;
				case isset(static::$findAlias[$args]) :
					$args = static::$findAlias[$args];
					break;
				case $args === self::ALL || $args === self::FIRST :
					if ($params == null)
						$args = array($args);
					else {
						array_unshift($params, $args);
						$args = $params;
					}
					break;
				case empty(static::$primaryKey) :
					$args = array(self::FIRST);
					break;
				default :
					$args = array(
						'in' => array(
							static::$primaryKey => func_get_args(),
						)
					);
					$args[0] = func_num_args() == 1 ? self::FIRST : self::ALL;
			}
		}
		// 分页
		if (isset($args['pageSize']) && is_numeric($args['pageSize'])) {
			$pager['pageSize'] = intval($args['pageSize']);
			if (!isset($args['pageCurrent'])) {
				if (!isset($args['pageParam']))
					$pager['pageParam'] = $args['pageParam'] = 'page';
				if (isset($data_cache['request'][$args['pageParam']]))
					$pager['pageCurrent'] = intval($data_cache['request'][$args['pageParam']]);
				else
					$pager['pageCurrent'] = 1;
			}
			else
				$pager['pageCurrent'] = intval($args['pageCurrent']);
			$pager['rsCount'] = static::rsCount($args);
			$pager['pageCount'] = (int)($pager['rsCount'] / $pager['pageSize']);
			if ($pager['rsCount'] % $pager['pageSize'] > 0) $pager['pageCount']++;
			if ($pager['pageCurrent'] < 1) $pager['pageCurrent'] = 1;
			elseif ($pager['pageCurrent'] > $pager['pageCount'] - 1) $pager['pageCurrent'] = $pager['pageCount'];
			$args['limit'] = $pager['pageSize'];
			$args['offset'] = ($pager['pageCurrent'] - 1) * $args['limit'];
		}
		$limit = empty($args['limit']) ? 0 : intval($args['limit']);
		// 既未指定MST_DBO::FIRST，也未指�?limit的时候，$limit = 1
		if (empty($args[0]) && $limit <= 0)
			$limit = 1;
		// 当find的参数，指定了MST_DBO::FIRST的时候，$limit = 1
		if (!empty($args[0]) && $args[0] == self::FIRST)
			$limit = 1;
		if (empty($args[0]))
			$args[0] = $limit > 1 ? self::ALL : self::FIRST;
		// find参数补充
		if (empty($args['select']))
			$args['select'] = static::$defaultSelect;
		if (empty($args['from']))
			$args['from'] = static::$table;
		elseif (stripos($args['from'], (string)static::$table) === false)
			$args['from'] = static::$table . ',' . $args['from'];
		$data = MST_DBC::connect(static::$remote)->select($args);
        if ($isArray || isset($args['asArray']))
            return $data;
        if (!empty($args[0]) && $args[0] == self::FIRST && empty($data))
            return new static();
        return self::newInstance($args, $data, $pager);
	}

    /**
     * 填充返回的数据集合
     * @param $args 查询结构参数
     * @param $data 数据库返回的原始数据
     * @param null $pager 分页数据
     * @return array
     */
    static protected function newInstance($args, $data, $pager = null) {
		if ($args[0] == self::FIRST) {
			if (static::$primaryKey == null || !isset($data[static::$primaryKey]))
				return new static::$dataSetClass($data);
			else {
				$obj = new static::$name($data);
				$obj->_shadow = $data;
				return $obj;
			}
		}
		else {
			$dataSet = new static::$dataSetClass();
			if (!empty($data)) {
				foreach ($data as $row) {
					if (static::$primaryKey == null || !isset($row[static::$primaryKey]))
						$dataSet[] = $row;
					else {
						$obj = new static::$name($row, false);
						$obj->_shadow = $data;
						$dataSet[] = $obj;
					}
				}
			}
			$dataSet->setPager($pager);
		}
		return $dataSet;
		/*
		global $DATA_CACHE;
		if (empty($DATA_CACHE[$hash])) {
			if ($args[0] == self::FIRST) {
				$DATA_CACHE[$hash] = new static::$name($data);
			}
			else {
				$DATA_CACHE[$hash] = new static::$dataSetClass();
				if (!empty($data)) {
					foreach ($data as $row) {
						$DATA_CACHE[$hash][] = new static::$name($row);
					}
				}
			}
		}
		return $DATA_CACHE[$hash];
		*/
	}

    /**
     * 获取查询影响的行数
     * @param null $args 查询结构参数
     * @return int
     */
    final static public function rsCount($args = null) {
		if ($args == null || !is_array($args)) $args = array();
		$args['select'] = 'COUNT(*) as rs_count';
		if (empty($args['select']))
			$args['select'] = static::$defaultSelect;
		if (empty($args['from']))
			$args['from'] = static::$table;
		$args[0] = self::FIRST;
		unset($args['order']);
		$db = MST_DBC::connect(static::$remote);
		if (isset($args['group'])) {
            $from = null;
            $params = null;
            $sql = null;
			$db->mkSelectSql($args, $from, $params);
			$db->mkSelectSql(array(
				'select' => 'COUNT(*) as rs_count',
				'from' => "({$from}) t_count",
			), $sql, $params);
			$db->query($sql, $params);
			$result = $db->fetch();
		}
		else
			$result = $db->select($args);
		if ($result == false)
			return 0;
		else
			return (int)$result['rs_count'];
	}

    /**
     * 插入数据
     * @param $data 数据结构
     * @return bool|MST_DBO_DataSet|static
     */
    static public function insert($data) {
		$argsNum = func_num_args();
		if ($argsNum == 1) {
			$inst = new static($data);
			if ($inst->upgrade())
				return $inst;
			else
				return false;
		}
		else {
			$args = new MST_DBO_DataSet(func_get_args());
			$success = 0;
			foreach ($args as & $data) {
				$data = static::insert($data);
				if ($data)
					$success++;
			}
			if ($success > 0)
				return $args;
			else
				return false;
		}
	}

    /**
     * 更新数据
     * @param $args 查询结构参数
     * @param array $data 需要更新的数据
     * @return bool|int|number
     */
    static public function update($args, array $data = null) {
		if ($data == null) return false;
		// 有主键的条件下，以args为主键查询
		if ((is_string($args) || is_numeric($args))
			&& static::$primaryKey != null)
		{
			$args = array('in' => array(static::$primaryKey => $args));
		}
		// 无主键的表，且传进来的是一个数组格式
		if (empty(static::$primaryKey) && !empty($args) && is_array($args)) {
			// $args里面没有in和where查询，且为数组类型
			if (!isset($args['where']) && !isset($args['in']))
				$args = array('in' => $args);
		}
		if ($args == null) return false;
		$count = static::rsCount($args);
		if ($count > 0) {
			// 创建�?��虚拟的对象进行数据验�?
			// 该实体的生命周期到afterSave()以后结束
			$inst = new static($data);
			// 进行验证
			if ($inst->beforeValidate($data, $args) === false) return false;
			$inst->validate($data);
			if ($inst->beforeUpdate($data, $args) === false
			 || $inst->beforeSave($data, $args) === false
			 || $inst->hasError()) return false;
			$count = MST_DBC::connect(static::$remote)->update(static::$table, $args, $data);
			if ($count > 0) {
				$inst->afterUpdate($data, $args);
				$inst->afterSave($data, $args);
			}
		}
		return $count;
	}

    /**
     * 流程控制钩子，删除之前回调
     * @param $args
     * @param $count
     */
    static protected function beforeDelete($args, $count) {}

    /**
     * 流程控制钩子，删除之后回调
     * @param $args
     * @param $count
     */
    static protected function afterDelete($args, $count) {}

    /**
     * 删除数据
     * @param $args 查询结构参数
     * @return bool|int|number
     */
    static public function delete($args) {
		if ((is_string($args) || is_numeric($args))
			&& static::$primaryKey != null)
		{
			$args = array('in' => array(static::$primaryKey => $args));
		}
		// 无主键的表，且传进来的是一个数组格式
		if (empty(static::$primaryKey) && !empty($args) && is_array($args)) {
			// $args里面没有in和where查询，且为数组类型
			if (!isset($args['where']) && !isset($args['in']))
				$args = array('in' => $args);
		}
		$count = static::rsCount($args);
		if ($count > 0) {
			// 预传$count为删前找到符合的数量
			if (static::beforeDelete($args, $count) === false) return false;
			$count = MST_DBC::connect(static::$remote)->delete(static::$table, $args);
			if ($count > 0)
				static::afterDelete($args, $count);
		}
		return $count;
	}

    /**
     * 开始事务
     * @return mixed
     */
    static public function startTransaction() {
		return MST_DBC::startTransaction(static::$remote);
	}

    /**
     * 提交事务
     * @return mixed
     */
    static public function commit() {
		return MST_DBC::commit(static::$remote);
	}

    /**
     * 回滚事务
     * @return mixed
     */
    static public function rollBack() {
		return MST_DBC::rollBack(static::$remote);
	}

    /**
     * 查询已经执行过的SQL语句
     * @param int $index 记录的索引值，按时间顺序
     * @return mixed
     */
    static public function lastSql($index = 0) {
		return MST_DBC::connect(static::$remote)->lastSql($index);
	}

    /**
     * 最后插入的记录主键值
     * @param null $column
     * @return mixed
     */
    static public function lastInsertId($column = null) {
		if (static::$isAutoInc) {
			if (empty($column) && !empty(static::$primaryKey))
				$column = static::$primaryKey;
			return MST_DBC::connect(static::$remote)->lastInsertId(static::$table, $column);
		}
	}

    /**
     * 流程控制钩子，构造填充数据时回调
     * @param $data
     */
    protected function onInitData(& $data) {}

    /**
     * 流程控制钩子，构造空Model时回调
     * @param $data
     */
    protected function onInitNullData(& $data) {}

    /**
     * 构造函数
     * @param null $data 原始数据记录
     */
    public function __construct($data = null) {
		if (!empty($data) && is_array($data)) {
			if (!empty(static::$LOBColumns)) {
				foreach (static::$LOBColumns as $column => $columnType) {
					if ($columnType == 'CLOB' && !empty($data[$column]))
						$data[$column] = stream_get_contents($data[$column]);
				}
			}
			if (!empty(static::$primaryKey) && !empty($data[static::$primaryKey]))
				$this->_pk = $data[static::$primaryKey];
			// 维持�?��clone版本
			//$this->_shadow = $data;
			//else
			//	$this->_pk = $data; /* 如果没有主键，只好把原数据作为pk参�? */
			$this->onInitData($data);
			parent::__construct($data);
		}
		else {
			$this->onInitNullData($data);
			if ($data != null)
				parent::__construct($data);
		}
		$this->setFlags(ArrayObject::ARRAY_AS_PROPS);
	}

    /**
     * 析构函数
     */
    public function __destruct() {
		if ($this->_validator != null) $this->_validator = null;
	}

    /**
     * 构造in查询结构
     * <code>
     * array(
     *     'in' => array(
                'field1' => 'value',
                'field2' => array('value1', 'value2'...),
     *      ),
     * );
     * </code>
     * @param $fields 字段集合
     * @param $values 对应的值集合
     * @return array|null
     */
    static public function combine($fields, $values) {
		if (empty($fields) || empty($values)) return null;
		$last = end($values);
		if (is_array($last)) {
			$params = $last;
			if (!isset($params['in']))
				$params['in'] = array();
		}
		else
			$params = array('in' => array());
		foreach ($fields as $key => $val) {
			if (isset($values[$key]))
				$params['in'][$val] = $values[$key];
		}
		return $params;
	}

    /**
     * 魔术方法，可以使用多种变体的func查询
     * <code>
     * //单条记录
     * model::find_by_field(array($value))
     * model::find_by_field1_and_field2[_and...](array($value1, $value2[...]))
     * //多条记录
     * model::find_all_by_field(array($value))
     * model::find_all_by_field1_and_field2[_and...](array($value1, $value2[...]))
     * </code>
     * @param $name
     * @param $args
     * @return array|MST_DataSet|MST_DBO
     */
    static public function  __callStatic($name, $args) {
		$name = strtolower($name);
		if (strpos($name, 'find_by_') === 0) {
			return static::find(static::FIRST, static::combine(explode('_and_', str_replace('find_by_', null, $name)), $args));
		}
		elseif (strpos($name, 'find_all_by_') === 0) {
			return static::find(static::ALL, static::combine(explode('_and_', str_replace('find_all_by_', null, $name)), $args));
		}
	}

    /**
     * 实现ArrayObject接口 模拟数组接口
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset) {
		switch (true) {
			case isset(static::$hasOne[$offset]) :
				return $this->getRelate(self::HAS_ONE, $offset, static::$hasOne[$offset]);
			case isset(static::$belongsTo[$offset]) :
				return $this->getRelate(self::BELONGS_TO, $offset, static::$belongsTo[$offset]);
			default :
				return parent::offsetGet($offset);
		}
	}

    /**
     * 获取model中的字段值 [三元式，一包含判断空逻辑并返回默认值]
     * @param $field 字段名称
     * @param null $default 默认返回值
     * @return null
     */
    public function prop($field, $default = null) {
		return isset($this[$field]) ? $this[$field] : $default;
	}


    /**
     * 实现ArrayObject接口 模拟数组接口
     * @param mixed $offset
     * @param mixed $val
     * @return bool|void
     */
    public function offsetSet($offset, $val) {
		if (!$this->isNew() && isset(static::$columns[$offset]['readonly']))
			return false;
		if (isset(static::$columns[$offset]['date'])) {
			if (is_numeric($val)) /* 这里以默认的格式输入,暂时不做解析 */
				$val = date('Y-m-d H:i:s', $val);
		}
		parent::offsetSet($offset, $val);
	}

    /**
     * 流程控制钩子，数据验证之前回调
     * @param $data
     * @param $args
     */
    protected function beforeValidate(& $data, $args) {}

    /**
     * 流程控制钩子，插入数据前回调
     * @param $data
     * @param $args
     */
    protected function beforeCreate(& $data, $args) {}

    /**
     * 流程控制钩子，更新数据前回调
     * @param $data
     * @param $args
     */
    protected function beforeUpdate(& $data, $args) {}

    /**
     * 流程控制钩子，保存数据[插入&更新]之前回调
     * @param $data
     * @param $args
     */
    protected function beforeSave(& $data, $args) {}

    /**
     * 流程控制钩子，插入数据之后回调
     * @param $data
     * @param $args
     */
    protected function afterCreate(& $data, $args) {}

    /**
     * 流程控制钩子，更新数据之后回调
     * @param $data
     * @param $args
     */
    protected function afterUpdate(& $data, $args) {}

    /**
     * 流程控制钩子，保存数据[插入&更新]之后回调
     * @param $data
     * @param $args
     */
    protected function afterSave(& $data, $args) {}

    /**
     * 流程控制钩子，销毁数据之前回调
     */
    protected function beforeDestroy() {}

    /**
     * 流程控制钩子，销毁数据之后回调
     */
    protected function afterDestroy() {}

    /**
     * 虚函数，用于form widget构造表单结构
     */
    public function getFormColumns() {}

    /**
     * 填充model数据
     * <code>
     * $model->assign(array(
     *      'field1' => 'value1',
     *      'field2' => 'value2',
     *      'field3' => 'value3',
     * ));
     *
     * //or
     *
     * $model->assign('field1', 'value1');
     *
     * </code>
     * @param string|array $key
     * @param null $val
     * @return $this
     */
    public function assign($key, $val = null) {
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				$this[$k] = $v;
			}
		}
		else {
			$this[$key] = $val;
		}
		return $this;
	}

    /**
     * 保存[插入or更新]数据
     * <code>
     * $model->upgrade(array(
     *      'field1' => 'value1',
     * ));
     * </code>
     * @param array $data 数据结构
     * @param null $isUpdateOnChange 是否只更细更改的数据
     * @return bool|int|number
     */
    public function upgrade(array $data = array(), $isUpdateOnChange = null) {
		if ($data != null)
			$this->assign($data);
		// @todo 这里可以分离出一个方�?
		$args = null;
		if (static::$primaryKey && $this->_pk != null) {
			$args = array('in' => array(static::$primaryKey => $this->_pk));
		}
		elseif (!empty($this->_shadow)) {
			$args = array('in' => $this->_shadow);
		}
		$data = (array)$this;
		$type = empty($args) ? 'Create' : 'Update';
		$beforeEvent = 'before' . $type;
		$afterEvent  = 'after' . $type;
		$result = 0;
		if ($this->beforeValidate($data, $args) === false) return false;
		$this->validate($data);
		if ($this->$beforeEvent($data, $args) === false
		 || $this->beforeSave($data, $args) === false
		 || $this->hasError()) return false;
		// 空的参数,表示创建�?��对象
		if ($type == 'Create') {
			// 写入时获取结�?
			if (static::$struct != null && $this->_struct == null)
				$this->_struct = array_combine(static::$struct, array_fill(0, count(static::$struct), null));
			if ($this->_struct != null && is_array($this->_struct))
				$data = array_merge($this->_struct, $data);
		}
		else {
			if ($isUpdateOnChange === null)
				$isUpdateOnChange = $this->isUpdateOnChange;
			if ($isUpdateOnChange) {
				$data = array_diff_assoc($data, $this->_shadow);
			}
		}
		if ($type == 'Create') {
			$result = MST_DBC::connect(static::$remote)->insert(static::$table, $data, static::$primaryKey, static::$isAutoInc);
			if ($result > 0) {
				if (static::$primaryKey != null && static::$isAutoInc)
					$this->_pk = $this[static::$primaryKey] = $this->lastInsertId();
			}
		}
		else {
			if (empty($data)) {
				$this->_noUpdateColumn = true;
				// 无更新字�?
				return true;
			}
			$result = MST_DBC::connect(static::$remote)->update(static::$table, $args, $data);
		}
		if ($result > 0) {
			// 如果是自动提交则更新
			$this->_shadow = array_merge((array)$this->_shadow, $data);
			$this->$afterEvent($data, $args);
			$this->afterSave($data, $args);
		}
		return $result;
	}

    /**
     * 销毁数据model对象
     * @return bool
     */
    public function destroy() {
		$args = array();
		if (static::$primaryKey && $this->_pk != null) {
			$args = array('in' => array(static::$primaryKey => $this->_pk));
		}
		elseif (!empty($this->_shadow)) {
			$args = array('in' => $this->_shadow);
		}
		$count = static::rsCount($args);
		if ($count > 0) {
			if ($this->beforeDestroy($args, $count) === false) return false;
			$count = MST_DBC::connect(static::$remote)->delete(static::$table, $args);
			if ($count > 0)
				$this->afterDestroy($args, $count);
		}
		return false;
	}


    /**
     * 是否新创建的数据
     * 注意，这个isNew表示的，对象没有写入到数据库，
     * 要判断一个对象实例是否为空，应该使用(object)MST_DBO->isEmpty()
     * @return bool
     */
    public function isNew() {
		return $this->_shadow == null;
	}

    /**
     * model的基础数据结构
     * @param array $struct
     */
    public function setStruct($struct) {
		if (is_array($struct))
			$this->_struct = $struct;
	}

    /**
     * 验证数据
     * @param null $data
     */
    public function validate(& $data = null) {
		if (empty($this->_validator))
			$this->_validator = new MST_DBO_Validator(static::$columns);
		if ($data == null)
			$data = (array)$this;
		$this->_validator->validate($data, null, $this->_pk);
	}

    /**
     * 获取数据验证器
     * @return MST_DBO_Validator
     */
    public function & getValidator() {
		if (empty($this->_validator))
			$this->_validator = new MST_DBO_Validator(static::$columns);
		return $this->_validator;
	}

	public function setError($key, $msg = null) {
		if (empty($this->_validator))
			$this->_validator = new MST_DBO_Validator(static::$columns);
		if (!empty(static::$columns[$key]) && !empty(static::$columns[$key]['title']))
			$msg = static::$columns[$key]['title'] . $msg;
		if ($msg == null)
			$this->_validator->setError(MST_DBO_Error::GENERAL, $key);
		else
			$this->_validator->setError($key, $msg);
		return $this;
	}

	public function getError($key) {
		if (empty($this->_validator)) return false;
		return $this->_validator->getError($key);
	}

    /**
     * 删除错误信息
     * @param $key
     */
    public function removeError($key) {
		return $this->_validator->clearError($key);
	}

    public function countError() {
        return $this->_validator->countError();
    }

    public function clearError($key = null) {
        return $this->_validator->clearError($key);
    }

	public function getErrors() {
		if (empty($this->_validator)) return false;
		return $this->_validator->getErrors();
	}

	public function hasError($key = null) {
		if (empty($this->_validator)) return false;
		return $this->_validator->hasError($key);
	}

}
