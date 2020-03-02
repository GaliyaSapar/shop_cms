<?php


namespace App\Repository;


use App\Db\MySQL;
use App\Model\Model;
use App\Model\ModelAbstract;

abstract class RepositoryAbstract
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @var MySQL
     */
    protected $mySQL;

    protected $table_name;

    public function __construct(MySQL $mySQL)
    {
        if (!class_exists($this->model) || !in_array(ModelAbstract::class, class_parents($this->model))) {
            throw new \Exception('model should extends Model');
        }

        $this->table_name = $this->getTableName();
        $this->mySQL = $mySQL;
    }
    
    public function findAll()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->mySQL->fetchAllHash($query, 'id', $this->model);

        return $this->modifyResultList($result);

    }
    
    public function findAllWithLimit(int $limit = 50, int $start = 0)
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id LIMIT $limit, $start";

        $result = $this->mySQL->fetchAllHash($query, 'id', $this->model);


        $result = $this->modifyResultList($result);

        return $result;

    }



    public function getCount(string $where = null)
    {
        $query = "SELECT COUNT(*) as count FROM products";

        if ($where) {
            $query .= $where;
        }

        /**
         * @var $result Model
         */
        $result = $this->mySQL->fetchRow($query, Model::class); //создается объект класса Модел, создается его свойство count

        return (int) $result->getProperty('count') ?? 0;
    }
    
    private function getTableName() 
    {
        $model = new $this->model;
        $object = new \ReflectionObject($model);
        $property = $object->getProperty('table_name');
        $property->setAccessible(true);
        
        return $property->getValue($model);
    }

    protected function modifyResultList(array $result) {
//        echo '<pre>'; var_dump($result); echo '</pre>';
        return $result;

    }

}