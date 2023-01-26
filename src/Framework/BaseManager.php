<?php
class BaseManager
{
    private $_table;
    private $_object;
    protected $_bdd;

    public function __construct($table, $object, $datasource)
    {
        $this->_table = $table;
        $this->_object = $object;
        $this->_bdd = BDD::getInstance($datasource);
    }

    public function getById($id)
    {
        $req = $this->_bdd->prepare("SELECT * FROM " . $this->_table . " WHERE id=?");
        $req->execute(array($id));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->_object);
        return $req->fetch();
    }

    public function getAll()
    {
        $req = $this->_bdd->prepare("SELECT * FROM " . $this->_table);
        $req->execute();
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->_object);
        return $req->fetchAll();
    }

    public function create($obj, $param)
    {

        $paramNumber = count($param);
        $valueArray = array_fill(1, $paramNumber, "?");
        $valueString = implode($valueArray, ", ");
        $sql = "INSERT INTO " . $this->_table . "(" . implode($param, ", ") . ") VALUES(" . $valueString . ")";
        $req = $this->_bdd->prepare($sql);
        $boundParam = array();
        foreach ($param as $paramName) {
            if (property_exists($obj, $paramName)) {
                $boundParam[$paramName] = $obj->$paramName;
            } else {
                throw new PropertyNotFoundException($this->_object, $paramName);
            }
        }
        $req->execute($boundParam);
    }

    public function update($obj, $param)
    {
        $sql = "UPDATE " . $this->_table . " SET ";
        foreach ($param as $paramName) {
            $sql = $sql . $paramName . " = ?, ";
        }
        $sql = $sql . " WHERE id = ? ";
        $req = $this->_bdd->prepare($sql);

        $param[] = 'id';
        $boundParam = array();
        foreach ($param as $paramName) {
            if (property_exists($obj, $paramName)) {
                $boundParam[$paramName] = $obj->$paramName;
            } else {
                throw new PropertyNotFoundException($this->_object, $paramName);
            }
        }

        $req->execute($boundParam);
    }

    public function delete($obj)
    {
        if (property_exists($obj, "id")) {
            $req = $this->_bdd->prepare("DELETE FROM " . $this->_table . " WHERE id=?");
            return $req->execute(array($obj->id));
        } else {
            throw new PropertyNotFoundException($obj, "id");
        }
    }
}