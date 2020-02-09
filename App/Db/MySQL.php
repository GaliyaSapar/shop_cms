<?php

namespace App\Db;

use App\Model\Product;

class MySQL
{
    private $host;
    private $username;
    private $password;
    private $db_name;

    private $connect;

    public function __construct(string $host, string $username, string $password, string $db_name)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->db_name = $db_name;
    }

    private function connect() {

        if (!$this->connect) {

            $this->connect = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);

//        $mysql_errno = mysqli_connect_errno();
//        if ($mysql_errno > 0) {

            if (!$this->connect) {
                die('MySQL connect error: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
            }
            mysqli_set_charset($this->connect, 'utf8');
        }

        return $this->connect;
    }

    public function query($query) {

        $result = mysqli_query($this->connect(), $query);
        $this->checkErrors($result);
        return $result;
    }

    private function checkErrors($mysqli_query) {
//        $this->connect(); ???

        if(!$mysqli_query) {
            // зачем создавать соединение, если в запросе есть создание
            $message = 'MySQL query error: (' . mysqli_errno($this->connect) . ') ' . mysqli_error($this->connect);
            throw new \Exception($message);
        }
    }

    public function fetchRow($query, string $class_name) {
        $result = $this->query($query);
        $this->checkModelClassExist($class_name);

        return mysqli_fetch_object($result, $class_name);
    }

    public function fetchAll($query, string $class_name) {
        $result = $this->query($query);

        $this->checkModelClassExist($class_name);

        $data=[];

        while ($row = mysqli_fetch_object($result, $class_name)) {
            $data[] = $row;
        }

        return $data;
    }

    public function fetchAllHash(string $query, string $hash_key, string $class_name) {
        $result = $this->query($query);

        $data = [];

        $this->checkModelClassExist($class_name);

        while($row = mysqli_fetch_object($result, $class_name)) {

            $key = $row->getProperty($hash_key);
            $data[$key] = $row;
        }

        return $data;
    }

    private function checkModelClassExist($class_name) {
        $class_exist = class_exists($class_name);

        if ($class_exist) {
            $model_class = IModel::class;
            //$cap_object = new $class_name; //? можно проверять без объекта по названию класса
            $is_model = in_array($model_class, class_implements($class_name));

            if (!$is_model) {
                throw new \Exception("Class \"{$class_name}\" not implements \" {$model_class}\"");
            }
        } else {
            throw new \Exception("Class '{$class_name}' not exist");
        }
    }

    public function insert(string $table_name, array $value) {
        $table_name = $this->escape($table_name);

        $columns = array_keys($value);

        $columns = array_map(function ($item) {
            return $this->escape($item);
        }, $columns);

        $columns = implode(',', $columns);

        $values = array_map(function ($item) {
            return $this->escape($item);
        } ,$value);

        $values = '\'' . implode('\',\'', $values) . '\'';

        $query = "INSERT INTO $table_name ($columns) VALUES ($values)";
        $this->query($query);

        return mysqli_insert_id($this->connect);
    }

    public function update(string $table_name, array $values, array $where = []) {
        $table_name = $this->escape($table_name);

        $set_data = [];

        foreach ($values as $key => $value) {
            $set_data[] = $this->escape($key) . ' = \'' . $this->escape($value) . '\'';

        }
            $set_data = implode(', ', $set_data);

            $where_data = [];

            foreach($where as $key => $value) {
                $where_data[] = $this->escape($key) . ' = \'' . $this->escape($value) . '\'';
            }
            $query = "UPDATE $table_name SET $set_data";

            if(!empty($where_data)) {
                $where_data = implode(' AND ', $where_data);
                $query .= ' WHERE ' . $where_data;
            }
            echo '<pre>'; var_dump($query); echo '/pre';
            $this->query($query);
    }

    public function delete(string $table_name, array $where = []) {
        $table_name = $this->escape($table_name);

        $where_data = [];

        foreach ($where as $key => $value) {
            $where_data[] = $this->escape($key) . ' = \'' . $this->escape($value) . '\'';
        }

        $query = "DELETE FROM $table_name";

        if (!empty($where_data)) {
            $where_data = implode(' AND ', $where_data);
            $query .= ' WHERE ' . $where_data;
        }

        $this->query($query);
    }

    public function escape(string $value) {
        return mysqli_real_escape_string($this->connect(), $value);
    }

}