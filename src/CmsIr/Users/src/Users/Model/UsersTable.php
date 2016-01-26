<?php
namespace CmsIr\Users\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;

class UsersTable extends ModelTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
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

    public function findByName($name)
    {
        $where = new \Zend\Db\Sql\Where();
        $where->addPredicate(
            new \Zend\Db\Sql\Predicate\Like('email', $name.'@%')
        );

        $select = $this->tableGateway->getSql()->select();
        $select->where($where);

        $resultSet = $this->tableGateway->selectWith($select);
        $entity = $resultSet->current();
        return $entity;
    }

    public function deleteUser($ids)
    {
        if(!is_array($ids)) {
            $ids = array($ids);
        }

        foreach($ids as $id) {
            $this->tableGateway->delete(array('id' => $id));
        }
    }

    public function saveUser(Users $user)
    {
        $data = array(
            'name' => $user->getName(),
            'surname'  => $user->getSurname(),
            'password'  => $user->getPassword(),
            'password_salt'  => $user->getPasswordSalt(),
            'email'  => $user->getEmail(),
            'email_confirmed'  => 1,
            'role'  => $user->getRole(),
            'active'  => 1,
            'filename'  => $user->getFilename(),
            'extra'  => $user->getExtra(),
        );

        $id = (int) $user->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User id does not exist');
            }
        }
    }

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
                ->where(new Predicate\Operator('role', Predicate\Operator::OP_LT, 4))
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
}