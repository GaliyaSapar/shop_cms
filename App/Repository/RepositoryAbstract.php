<?php


namespace App\Repository;


use App\Db\MySQL;
use App\Model\AbstractEntity;
use App\Model\Model;
use App\Model\ModelAbstract;
use App\MySQL\ObjectDataManager;
use App\Service\RequestService;
use GivenClassNotImplementerITableRowException;
use QueryException;

abstract class RepositoryAbstract
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @var IObjectDataManager
     */
    protected $odm;

    protected $table_name;

    /**
     * RepositoryAbstract constructor.
     * @param IObjectDataManager $odm
     * @throws \Exception
     */
    public function __construct(IObjectDataManager $odm)
    {
        if (!class_exists($this->model) || !in_array(AbstractEntity::class, class_parents($this->model))) {
            throw new \Exception('model should extends AbstractEntity');
        }

        $this->table_name = $this->getTableName();
        $this->odm = $odm;
    }

    /**
     * @param AbstractEntity $entity
     * @return AbstractEntity
     */
    public function save(AbstractEntity $entity): AbstractEntity
    {
        /**
         * @var $result AbstractEntity
         */
        $result = $this->odm->save($entity);
    }

    /**
     * @param AbstractEntity $entity
     * @return int
     */
    public function delete(AbstractEntity $entity): int
    {
        return $this->odm->delete($entity);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \GivenClassNotImplementerITableRowException
     * @throws \QueryException
     */
    public function find(int $id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = $id";

        $result = $this->odm->fetchRow($query, $this->model);

        return $this->modifyResultItem($result);
    }

    /**
     * @return mixed
     */
    public function create() {
        return new $this->model;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \GivenClassNotImplementerITableRowException
     * @throws \QueryException
     */
    public function findOrCreate(int $id) {
        if ($id > 0) {
            return $this->find($id);
        }

        return $this->create();
    }

    /**
     * @return array
     * @throws \GivenClassNotImplementerITableRowException
     * @throws \QueryException
     */
    public function findAll()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->odm->fetchAllHash($query, 'id', $this->model);

        return $this->modifyResultList($result);

    }

    /**
     * @param int $limit
     * @param int $start
     * @return \App\MySQL\Interfaces\ITableRow[]|array
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function findAllWithLimit(int $limit = 50, int $start = 0)
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id LIMIT $limit, $start";

        $result = $this->odm->fetchAllHash($query, 'id', $this->model);


        $result = $this->modifyResultList($result);

        return $result;

    }

    /**
     * @param string|null $where
     * @return int
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function getCount(string $where = null)
    {
        $query = "SELECT COUNT(*) as count FROM products";

        if ($where) {
            $query .= $where;
        }

        /**
         * @var $result Model
         */
        $result = $this->odm->fetchRow($query, Model::class); //создается объект класса Модел, создается его свойство count

        return (int) $result->getColumnValue('count') ?? 0;
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    private function getTableName() 
    {
        $model = new $this->model;
        $object = new \ReflectionObject($model);
        $property = $object->getProperty('table_name');
        $property->setAccessible(true);
        
        return $property->getValue($model);
    }

    protected function modifyResultItem(AbstractEntity $item)
    {
        $list = [
            0 => $item,
        ];

        $result = $this->modifyResultList($list);

        return $result[0];
    }

    protected function modifyResultList(array $result) {
//        echo '<pre>'; var_dump($result); echo '</pre>';
        return $result;

    }

}