<?php

namespace App\Model;

use App\Db\IModel;

class ModelAbstract implements IModel {

    protected $table_name;

    /**
     * @var array
     */
    protected $table_fields;

    /**
     * @var array
     */
    protected $immutable_table_fields;

    public function getProperty(string $key)
    {
        return $this->$key;
    }

    /**
     * @return array
     */
    public function getTableFields(): array
    {
        return $this->table_fields;
    }

    /**
     * @return array
     */
    public function getImmutableTableFields(): array
    {
        return $this->immutable_table_fields;
    }



}
