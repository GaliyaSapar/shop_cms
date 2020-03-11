<?php


namespace App\Model;


use App\MySQL\Interfaces\ITableRow;

interface IEntity extends ITableRow, \ArrayAccess
{

}