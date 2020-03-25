<?php

class Database extends mysqli
{
    private static $instance;

    public static function Instance()
    {
        if (self::$instance == null)
            self::$instance = new self();

        return self::$instance;
    }

    public function __construct()
    {
        $params = include(ROOT . '/config/db_params.php');

        parent::__construct($params['host'], $params['user'], $params['password']);
        if ($this->connect_errno) {
            die('No connect with database');
        }
        $this->query('SET NAMES utf8');
        $this->set_charset('utf8');
        $this->select_db($params['dbname']) or die("No databse");

    }

    public function Select($sql)
    {
        $data = $this->query($sql);

        if (!$data)
            die($this->error);

        $n = $data->num_rows;
        $arr = array();

        for($i = 0; $i < $n; $i++)
        {
            $row = $data->fetch_assoc();
            $arr[] = $row;
        }

        return $arr;
    }

    public function Insert($table, $array)
    {
        $columns = [];
        $values = [];

        foreach ($array as $key => $value)
        {
            $key = $this->real_escape_string($key . '');
            $columns[] = "`$key`";

            if ($value === null)
            {
                $values[] = 'NULL';
            }
            else{
                $value = $this->real_escape_string($value . '');
                $values[] = "'$value'";
            }
        }

        $columns_s = implode(',', $columns);
        $values_s = implode(',', $values);

        $query = "INSERT INTO $table ($columns_s) VALUES ($values_s)";
        $result = $this->query($query);

        if (!$result)
            die($this->error);

        return $this->insert_id;
    }


    public function Update($table, $array, $where)
    {
        $sets = [];

        foreach ($array as $key => $value)
        {
            $key = $this->real_escape_string($key . '');

            if ($value === null)
            {
                $sets[] = "$value=NULL";
            }
            else
            {
                $value = $this->real_escape_string($value . '');
                $sets[] = "`$key`='$value'";
            }
        }

        $sets_s = implode(',', $sets);
        $query = "UPDATE $table SET $sets_s WHERE $where";
        $result = $this->query($query);

        if (!$result)
            die($this->error);

        return $this->affected_rows;
    }


    public function Delete($table, $where)
    {
        $query = "DELETE FROM $table WHERE $where";
        $result = $this->query($query);

        if (!$result)
            die($this->error);

        return $this->affected_rows;
    }

}