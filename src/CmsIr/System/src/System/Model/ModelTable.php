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

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModelTable implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
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

    public function getBy($where, $order = null, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where($where);

        if (!empty($order))
        {
            $select->order($order);
        }

        if (!empty($limit))
        {
            $select->limit($limit);
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
                if(preg_match_all("/[A-Z]/", $sortingColumn, $matches) !== 0)
                {
                    $sortingColumn = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $sortingColumn));
                }
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
            if(strpos($columns[$i], 'status') === false && strpos($columns[$i], 'groups') === false)
            {
                $where[] = new Predicate\Like($columns[$i], '%'.$data->sSearch.'%');
            }
        }
        return $where;
    }

    public function getDataToDisplay ($filteredRows, $columns)
    {
        $dataArray = array();
        foreach($filteredRows as $row) {

            $tmp = array();
            foreach($columns as $column){
                $column = 'get'.ucfirst($column);
                if($column == 'getStatus')
                {
                    $tmp[] = $this->getLabelToDisplay($row->getStatusId());
                } elseif($column == 'getGroups')
                {
                    $tmp[] = $this->getGroupsToDisplay($row->getGroups());

                } else
                {
                    $tmp[] = $row->$column();
                }
            }

            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getLabelToDisplay ($labelValue)
    {
        $status = $this->getStatusTable()->getBy(array('id' => $labelValue));
        $currentStatus = reset($status);
        $currentStatus->getName() == 'Active' ? $checked = 'label-primary' : $checked = 'label-default';
        $currentStatus->getName() == 'Active' ? $name = 'Aktywny' : $name= 'Nieaktywny';

        $template = '<span class="label ' . $checked . '">' .$name . '</span>';
        return $template;
    }

    public function getGroupsToDisplay ($groups)
    {
        $subscriberGroups = unserialize($groups);
        if(!is_array($subscriberGroups)) $subscriberGroups = array($subscriberGroups);
        $template = '';
        foreach($subscriberGroups as $groupId) {
            $gruopName = $this->getSubscriberGroupTable()->getOneBy(array('id' => $groupId));
            $template .= '<span class="label label-info">' . $gruopName->getName() . '</span> ';
        }

        return $template;
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberGroupTable
     */
    public function getSubscriberGroupTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberGroupTable');
    }

    /**
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

}