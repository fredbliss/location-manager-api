<?php
use Aura\Sql\ExtendedPdo;

class Helpers {

    protected $container;

    public function __construct($c) {
        $this->container = $c;
    }

    public function getAll($table, $where = '')
    {
        $pdo = $this->container['db'];
        $stm = "SELECT * FROM " . $table . $where;
        $sth = $pdo->prepare($stm);

        $sth->execute();

        return ($sth ? $sth->fetchAll(PDO::FETCH_ASSOC) : false);
    }

    public function getOne($table, $wherekey, $value, $fields = '*') {
        $pdo = $this->container['db'];
        $sth = $pdo->prepare("SELECT ".$fields." FROM ".$table." WHERE ".$wherekey." =:".$wherekey);
        $sth->bindParam("id", $value);
        $sth->execute();


        return ($sth ? $sth->fetchObject() : false);
    }
}