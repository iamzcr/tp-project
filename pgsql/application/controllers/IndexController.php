<?php
class IndexController extends __Application {

    public
        $data,
        $list,
        $layout = 'default';

    public function indexAction() {

/*        Test::startTransaction();
        foreach(range(1,99) as $row) {
            $item = new Test();
            $item->assign(array(
                'text' => 'For Text :'.$row,
                'json_src' => json_encode(array(
                    'id' => $row,
                    'text' => 'Hello World! '.rand(100, 999),
                )),
            ))->upgrade();
        }
        Test::commit();*/

/*        PgSQLHelper::create_json_index(new Test(),
            PgSQLHelper::mk_json_column('json_src', array('id'), PgSQLHelper::TYPE_INT), 'item_id');*/

        $select = 'id, text, '.
            PgSQLHelper::mk_json_column('json_src', array('id'), PgSQLHelper::TYPE_INT, 'id_in_json').', json_src, created_at';
        $where = array('1=? AND '.PgSQLHelper::mk_json_column('json_src', array('id'), PgSQLHelper::TYPE_INT).' > ?', 1, 50);

        $this->list = Test::find(Test::ALL, array(
            'select' => $select,
            'where' => $where,
            'pageSize' => 10,
            'order' => 'id ASC',
        ));

        var_export(Test::lastSql());
    }


    public function editAction() {
        $this->data = Test::find($this->params->target);

        if ($this->params->isPost('test')) {
            $this->data->upgrade($this->params->p('test'));
        }
    }


    public function mkindexAction() {
        $this->data = Test::find($this->params->target);
        $name = $this->params->g('name');
        if ($name) {
            $sql = PgSQLHelper::mk_json_column('json_src', array($name));
            $name = str_replace(' ', '_', $name);
            PgSQLHelper::create_json_index($this->data, $sql, $name);
        }
    }
}