<?php
class Test extends MST_DBO {

    protected static
        $table = 'test',
        $remote = 'pgsql',
        $columns = array(
            'text' => array('text', 'title' => 'Text', 'require' => 1,),
            'json_src' => array('text', 'title' => 'JSON','require' => 1,),
            'created_at' => array('datetime', 'title' => 'Created Time', 'require' => 0, 'int2date' => 'Y-m-d H:i:s'),
        ),
        $primaryKey = 'id',
        $struct  = array('text', 'json_src','created_at'),
        $defaultSelect = 'id,text,json_src,created_at';

    protected function beforeCreate(&$data, $args) {
        if (!isset($data['created_at']))
            $data['created_at'] = time();
    }

    protected function beforeSave(& $data, $args) {
        if (isset($data['json_src']) && is_array($data['json_src'])) {
            if (isset($data['json_src']['name'])) {
                $json_src = array();
                foreach($data['json_src']['name'] as $i => $row) {
                    $json_src[$row] = $data['json_src']['value'][$i];
                }
                $data['json_src'] = json_encode($json_src);
                $this['json_src'] = $data['json_src'];
            }
        }
    }

    static public function getGridHeads() {
        $heads = self::getAllColumnOptions();
        return $heads;
    }

    public function  getFormColumns() {
        $columns = self::$columns;
        return $columns;
    }

    /**
     * @return MST_IDBC
     */
    static public function get_dbc() {
        return MST_DBC::connect(static::$remote);
    }

    static public function get_table() {
        return static::$table;
    }

    static public function get_prefix() {
        return static::$prefix;
    }
}