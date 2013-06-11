<?php
require_once "dbc.php";


class BaseClass
{

    function __set($name,  $value)
    {
        $this -> $name = $value;
    }

    public $id;
    protected $query;
    public $where;
    protected $connect;

    //Update Where if values changes from __construct
    public function setWhere($what,  $where)
    {
        $this -> where = array($what => $where);
        return $this;
    }

    public function getInstance()
    {

        if ($this -> connect == 'mantis') {
            global $pdo_mantis;
            $connection = $pdo_mantis;
        } elseif ($this -> connect == 'gitlab') {
            global $pdo_gitlab;
            $connection = $pdo_gitlab;
        }

        try {
            $stmt = $connection -> prepare($this -> query);
            $stmt -> execute($this -> where);
            $stmt -> setFetchMode(MyPDO::FETCH_CLASS,  get_class($this));
            $return = $stmt -> fetch();
            return $return;

        } catch (MyPDOException $e) {
            return 'Error: ' . $e -> __toString();
        }

    }

    public function getInstances()
    {
        if ($this -> connect == 'mantis') {
            global $pdo_mantis;
            $connection = $pdo_mantis;
        } elseif ($this -> connect == 'gitlab') {
            global $pdo_gitlab;
            $connection = $pdo_gitlab;
        }

        try {
            $stmt = $connection -> prepare($this -> query);
            $stmt -> execute($this -> where);
            $stmt -> setFetchMode(MyPDO::FETCH_CLASS,  get_class($this));
            $return = $stmt -> fetchAll();
            return $return;

        } catch (MyPDOException $e) {
            return 'Error: ' . $e -> __toString();
        }

    }

}
?>