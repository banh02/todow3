<?php

class BaseModel
{
    protected $connection;

    private $host = "localhost";
    private $dbname = "manage_work";
    private $usernameDB = "root";
    private $passwordDB = "";
    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->host; dbname=$this->dbname;charset=utf8", $this->usernameDB, $this->passwordDB);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }


    }

    //Phuong thuc lay toan bo du lieu
    public static function getALL()
    {
        $model = new static();
        $sql = "SELECT * FROM $model->tableName";
        $stmt = $model->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, get_class($model));
        return $result;
    }

    public static function find($id)
    {
        $model = new static();
        $sql = "SELECT * FROM $model->tableName WHERE id = $id";
        $stmt = $model->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, get_class($model));
        // var_dump($result);die;
        if (count($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }

    public function delete()
    {
        $this->queryBuilder = "DELETE FROM $this->tableName WHERE id = $this->id";
        $stmt = $this->connection->prepare($this->queryBuilder);
        try {
            $stmt->execute();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function update()
    {
        $this->queryBuilder = "UPDATE $this->tableName SET ";

        foreach ($this->columns as $col) {
            if ($this->{$col} == null) {
                continue;
            }
            $this->queryBuilder .= " $col = '" . $this->{$col} . "', ";
        }
        $this->queryBuilder = rtrim($this->queryBuilder, ", ");
        $this->queryBuilder .= " WHERE id = $this->id";

        $stmt = $this->connection->prepare($this->queryBuilder);
        try {
            $stmt->execute();
            return $this;
        } catch (Exception $ex) {
            return null;
        }
    }


    public function create()
    {
        $this->queryBuilder = "INSERT INTO $this->tableName (";
        foreach ($this->columns as $col) {
            if ($this->{$col} == null && !is_string($this->{$col}))
                continue;
            $this->queryBuilder .= "$col, ";
        }
        $this->queryBuilder = rtrim($this->queryBuilder, ", ");
        $this->queryBuilder .= ") VALUES ( ";
        foreach ($this->columns as $col) {
            if ($this->{$col} == null)
                continue;
            $this->queryBuilder .= "'" . $this->{$col} . "', ";
        }
        $this->queryBuilder = rtrim($this->queryBuilder, ", ");
        $this->queryBuilder .= ")";

        $stmt = $this->connection->prepare($this->queryBuilder);
        try {

            $stmt->execute();
            $this->id = $this->connection->lastInsertId();

            return $this;
        } catch (Exception $ex) {
            return null;
        }
    }

}