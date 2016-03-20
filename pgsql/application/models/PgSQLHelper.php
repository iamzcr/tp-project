<?php
class PgSQLHelper {

    const
        TYPE_INT = 'int',
        TYPE_STRING = 'string',
        TYPE_BOOL = 'bool';

    /**
     * 生成JSON数据SQL字段串
     *
     * @param $json_column JSON字段
     * @param array $paths JSON层级路径
     * @param string $type 数据类型转换
     * @param null $as_name 别名
     * @return string 生成JSON内容SQL字段串
     */
    static public function mk_json_column($json_column, array $paths = array(), $type = self::TYPE_STRING, $as_name = null) {
        $paths = array_map(function($item){
            return "'{$item}'";
        }, $paths);
        $target = array_pop($paths);
        array_unshift($paths, $json_column);
        $path_string = implode('->', $paths);
        $str =  $path_string.'->>'.$target;
        if ($type == self::TYPE_STRING) {
            return $str;
        }
        if ($type == self::TYPE_INT) {
            $str = 'CAST ('.$str.' as integer)';
        }
        if ($type == self::TYPE_BOOL) {
            $str = 'CAST ('.$str.' as boolean)';
        }

        if (empty($as_name)) {
            return $str;
        } else {
            return $str.' as '.$as_name;
        }
    }

    static public function create_json_index(MST_DBO $model, $json_SQL, $index_name = null) {
        $model_class = get_class($model);
        /**
         * @var $pgsql_dbc MST_IDBC
         */
        $pgsql_dbc = $model_class::get_dbc();
        $table_name = $model_class::get_prefix().$model_class::get_table();
        if (empty($index_name)) {
            $index_name = rand(10000, 99999);
        }
        $sql = "CREATE INDEX index_{$table_name}_{$index_name} ON {$table_name} (({$json_SQL}));";
        $pgsql_dbc->execute($sql);
    }
}