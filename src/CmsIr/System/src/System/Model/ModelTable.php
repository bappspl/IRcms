<?php

namespace CmsIr\System\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;

class ModelTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getAll()
    {
        $resultSet = $this->tableGateway->select();

        $result = $this->getResultSetAsArrayObject($resultSet);

        return $result;
    }

    public function countRowsWhere(array $where)
    {

        $select = $this->tableGateway->getSql()->select();
        $select->where($where);
        $select->columns(array('row_count' => new Expression('COUNT(1)')));
        $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $row = $result->current();
        return $row['row_count'];
    }

    public function getBy($where, $order = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where($where);

        if (!empty($order))
        {
            $select->order($order);
        }

        $resultSet = $this->tableGateway->selectWith($select);

        $result = array();

        foreach($resultSet as $entity)
        {
            $result[$entity->getId()] = $entity;
        }

        return $result;
    }

    public function getOneBy($where, $order = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where($where);

        if (!empty($order))
        {
            $select->order($order);
        }

        $resultSet = $this->tableGateway->selectWith($select);
        $entity = $resultSet->current();
        return $entity;
    }

    public function getByAndCount($where, $order = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where($where);

        if (!empty($order))
        {
            $select->order($order);
        }

        $resultSet = $this->tableGateway->selectWith($select);

        $result = array();

        $count = 0;

        foreach($resultSet as $entity)
        {
            $count++;
        }

        return $count;
    }

    public function getOneRandomBy($where)
    {
        $result = $this->tableGateway->select(function(Select $select) use ($where){

            $select->where($where);

            $rand = new \Zend\Db\Sql\Expression('RAND()');

            $select->order($rand);
            $select->limit(1);

        });

        $entity = $result->current();

        if(empty($entity))
        {
            return false;
        }

        $result = array();

        $result[$entity->getId()] = $entity;

        return $result;
    }

    public  function getRandomBy($where, $count)
    {
        $result = $this->tableGateway->select(function(Select $select) use ($where, $count){

            $select->where($where);

            $rand = new \Zend\Db\Sql\Expression('RAND()');

            $select->order($rand);
            $select->limit($count);

        });

        $entity = $result->current();

        if(empty($entity))
        {
            return false;
        }

        $result = array();

        $result[$entity->getId()] = $entity;

        return $result;
    }

    public function getResultSetAsArrayObject($resultSet)
    {
        $objectArray = array();

        foreach($resultSet as $result){
            array_push($objectArray, $result);
        }

        return $objectArray;

//        return count($objectArray) == 1 ? reset($objectArray) : $objectArray;
    }
}