<?php


namespace App\MySQL;


use App\MySQL\Interfaces\IArrayDataManager;
use App\MySQL\Interfaces\IConnection;
use QueryException;

class ArrayDataManager implements IArrayDataManager
{
    /**
     * @var IConnection
     */
    protected $connection;

    public function __construct(IConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $query
     * @return array
     *
     * @throws QueryException
     */
    public function fetchRow(string $query): array
    {
        $result = $this->query($query);
        return mysqli_fetch_array($result, MYSQLI_ASSOC );
    }

    /**
     * @param string $query
     * @return array
     *
     * @throws QueryException
     */

    public function fetchAll(string $query): array
    {
        $result = $this->query($query);

        $data=[];

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * @param string $query
     * @param string $hash_key
     * @return array
     *
     * @throws QueryException
     */
    public function fetchAllHash(string $query, string $hash_key): array
    {
        $result = $this->query($query);

        $data = [];

        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $key = $row[$hash_key] ?? null;

            if (is_null($key)) {
                continue;
            }

            $data[$key] = $row;
        }

        return $data;
    }

    /**
     * @param string $table_name
     * @param array $value
     * @return int
     *
     * @throws QueryException
     */
    public function insert(string $table_name, array $value): int
    {
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

        return mysqli_insert_id($this->connection);
    }

    /**
     * @param string $table_name
     * @param array $values
     * @return array
     * @throws QueryException
     */
    public function insertMany(string $table_name, array $values): array
    {
        $inserted_ids = [];

        foreach($values as $value) {
            $inserted_ids[] = $this->insert($table_name, $value);
        }
        return $inserted_ids;
    }

    /**
     * @param string $table_name
     * @param array $values
     * @param array $where
     * @return int
     * @throws QueryException
     */
    public function update(string $table_name, array $values, array $where = []): int
    {
        $table_name = $this->escape($table_name);

        $set_data = [];

        foreach ($values as $key => $param_value) {
            $set_data[] = $this->escape($key) . ' = \'' . $this->escape($param_value) . '\'';

        }
        $set_data = implode(', ', $set_data);

        $where_data = [];

        foreach($where as $key => $param_value) {
            $where_data[] = $this->escape($key) . ' = \'' . $this->escape($param_value) . '\'';
        }
        $query = "UPDATE $table_name SET $set_data";

        if(!empty($where_data)) {
            $where_data = implode(' AND ', $where_data);
            $query .= ' WHERE ' . $where_data;
        }

        $this->query($query);
        return mysqli_affected_rows($this->connection->getConnect());
    }

    /**
     * @param string $table_name
     * @param array $where
     * @return int
     * @throws QueryException
     */
    public function delete(string $table_name, array $where = []): int
    {
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
        return mysqli_affected_rows($this->connection->getConnect());
    }

    public function escape(string $value) {
        return mysqli_real_escape_string($this->connection->getConnect(), $value);
    }

    /**
     * @param string $query
     * @return bool|\mysqli_result
     *
     * @throws QueryException
     */
    protected function query(string $query) {
        $result = mysqli_query($this->connection->getConnect(), $query);
        $this->checkErrors($result);
        return $result;
    }

    /**
     * @param $mysqli_query
     * @throws QueryException
     */
    private function checkErrors($mysqli_query) {

        if(!$mysqli_query) {
            $message = 'MySQL query error: (' . mysqli_errno($this->connection->getConnect()) . ') ' . mysqli_error($this->connection->getConnect());
            throw new QueryException($message);
        }
    }
}