<?php

namespace CmsIr\System\Model;

use CmsIr\Post\Model\Post;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

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

    public function getWithPaginationBy($object, $where, $order = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where($where);

        if (!empty($order))
        {
            $select->order($order);
        }

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype($object);
        $resultSetPrototype->buffer();

        $paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), $resultSetPrototype);
        $paginator = new Paginator($paginatorAdapter);

        return $paginator;
    }

    // datatables

    public function getDatatables($columns, $data)
    {
        $displayFlag = false;

        $allRows = $this->getAll();
        $countAllRows = count($allRows);

        $trueOffset = (int) $data->iDisplayStart;
        $trueLimit = (int) $data->iDisplayLength;

        $sorting = array('id', 'asc');
        if(isset($data->iSortCol_0)) {
            $sorting = $this->getSortingColumnDir($columns, $data);
        }

        $where = array();
        if ($data->sSearch != '') {
            $where = array(
                new Predicate\PredicateSet(
                    $this->getFilterPredicate($columns, $data),
                    Predicate\PredicateSet::COMBINED_BY_OR
                )
            );
            $displayFlag = true;
        }

        $filteredRows = $this->tableGateway->select(function(Select $select) use ($trueLimit, $trueOffset, $sorting, $where){
            $select
                ->where($where)
                ->order($sorting[0] . ' ' . $sorting[1])
                ->limit($trueLimit)
                ->offset($trueOffset);
        });

        $dataArray = $this->getDataToDisplay($filteredRows, $columns);

        if($displayFlag == true) {
            $countFilteredRows = $filteredRows->count();
        } else {
            $countFilteredRows = $countAllRows;
        }

        return array('iTotalRecords' => $countAllRows, 'iTotalDisplayRecords' => $countFilteredRows, 'aaData' => $dataArray);
    }

    public function getSortingColumnDir ($columns, $data)
    {
        for ($i=0 ; $i<intval($data->iSortingCols); $i++)
        {
            if ($data['bSortable_'.intval($data['iSortCol_'.$i])] == 'true')
            {
                $sortingColumn = $columns[$data['iSortCol_'.$i]];
                $sortingDir = $data['sSortDir_'.$i];
                return array($sortingColumn, $sortingDir);
            }
        }
        return array();
    }

    public function getFilterPredicate ($columns, $data)
    {
        $where = array();
        for ( $i=0 ; $i<count($columns) ; $i++ )
        {
            $where[] = new Predicate\Like($columns[$i], '%'.$data->sSearch.'%');
        }
        return $where;
    }

}