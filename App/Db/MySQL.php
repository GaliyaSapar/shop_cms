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
        if ($this->connect) {
            return;
        }


        //

        $this->connect = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);

//        $mysql_errno = mysqli_connect_errno();

//        if ($mysql_errno > 0) {
        if(!$this->connect) {
            die('MySQL connect error: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }

        mysqli_set_charset($this->connect, 'utf8');
    }

    public function query($query) {
        $this->connect();

        $result = mysqli_query($this->connect, $query);

        $this->checkErrors($result);

        return $result;
    }

    private function checkErrors($mysqli_query) {
//        $this->connect(); ???

        if(!$mysqli_query) {

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
            $cap_object = new $class_name; //? можно проверять без объекта по названию класса
            $is_model = in_array($model_class, class_implements($class_name));

            if (!$is_model) {
                throw new \Exception("Class \"{$class_name}\" not implements \" {$model_class}\"");
            }
        } else {
            throw new \Exception("Class '{$class_name}' not exist");
        }
    }

}