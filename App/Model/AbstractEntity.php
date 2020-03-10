<?php


namespace App\Model;


class AbstractEntity implements IEntity
{
    /**
     * @var
     */
    protected $table_name;

    /**
     * @var string
     */
    protected $primary_key = 'id';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $table_fields;

    /**
     * @var array
     */
    protected $immutable_table_fields;

    public function getTableName(): string
    {
        return $this->table_name;
    }

    public function getPrimaryKey(): string
    {
        return $this->primary_key;
    }

    public function getPrimaryKeyValue(): string
    {
        return $this->{$this->getPrimaryKey()};
    }

    public function getColumnValue(string $key): string
    {
        return (string) $this->$key;
    }

    public function getColumnsForUpdate(): array
    {
        return array_diff_assoc($this->getColumnsForInsert(), $this->immutable_table_fields);
    }

    public function getColumnsForInsert(): array
    {
        return $this->table_fields;
    }
}