<?php
class BDD
{
    private $_bdd;
    private static $_instance = null;

    public static function getInstance($datasource)
    {
        if (empty(self::$_instance)) {
            self::$_instance = new BDD($datasource);
        }
        return self::$_instance->_bdd;
    }
    private function __construct($datasource)
    {
        $this->_bdd = new PDO(
            'mysql:dbname=' . $datasource['name'] . ';host=' . $datasource['host'],
            $datasource['user'],
            $datasource['password']
        );

    }
}