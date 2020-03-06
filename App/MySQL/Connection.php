<?php


namespace App\MySQL;


use App\MySQL\Interfaces\IConnection;
use ConnectionException;

class Connection implements IConnection
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $user_name;

    /**
     * @var string
     */
    private $user_pwd;

    /**
     * @var resource
     */
    private $connection;

    public function __construct(string $host, string $database, string $user_name, string $user_pwd)
    {
        $this->host = $host;
        $this->database = $database;
        $this->user_name = $user_name;
        $this->user_pwd = $user_pwd;
    }

    /**
     * @return resource
     * @throws ConnectionException
     */
    public function getConnect()
    {
        if (is_null($this->connection)) {
            $this->connect();
        }
        return $this->connection;
    }

    private function connect() {
        $this->connection = mysqli_connect($this->host, $this->user_name, $this->user_pwd, $this->database);

        if (!$this->connection) {
            throw new ConnectionException('MySQL connect error: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }
        mysqli_set_charset($this->connect, 'utf8');

    }


}