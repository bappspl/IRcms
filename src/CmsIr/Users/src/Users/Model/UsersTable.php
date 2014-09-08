<?php
namespace CmsIr\Users\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class UsersTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
	
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function findBy($limit, $offset)
    {
        $allRows = $this->fetchAll();
        $countAllRows = $allRows->count();

        $trueLimit = (int) $limit;
        $trueOffset = (int) $offset;
        $filteredRows = $this->tableGateway->select(function(Select $select) use ($trueLimit, $trueOffset){
            $select
                ->limit($trueLimit)
                ->offset($trueOffset);
        });

        $dataArray = array();
        foreach($filteredRows as $data) {
            $tmp = array($data->name, $data->surname, $data->email, $data->active);
            array_push($dataArray, $tmp);
        }

        $countFilteredRows = $filteredRows->count();

        return array('iTotalRecords' => $countAllRows, 'iTotalDisplayRecords' => $countFilteredRows, 'aaData' => $dataArray);
    }
}