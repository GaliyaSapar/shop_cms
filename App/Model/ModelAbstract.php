<?php

namespace App\Model;

use App\Db\IModel;

class ModelAbstract implements IModel {

    protected $table_name;

    public function getProperty(string $key)
    {
        return $this->$key;
    }

}
