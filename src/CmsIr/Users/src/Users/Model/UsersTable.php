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

    public function deleteUser($id)
    {
        $id  = (int) $id;
        $this->tableGateway->delete(array('id' => $id));
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
            'website_id'  => $user->getWebsiteId(),
            'dictionary_position_id'  => $user->getDictionaryPositionId(),
            'dictionary_group_id'  => $user->getDictionaryGroupId(),
            'position_description'  => $user->getPositionDescription(),
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

    public function getDataToDisplay ($filteredRows, $columns)
    {
        $dataArray = array();
        foreach($filteredRows as $row) {

            $tmp = array();
            foreach($columns as $column){
                $column = 'get'.ucfirst($column);
                $tmp[] = $row->$column();
            }
            $tmp[] = '<a href="users/preview/'.$row->getId().'" class="btn btn-info" data-toggle="tooltip" title="Podgląd"><i class="fa fa-eye"></i></a> ' .
                '<a href="users/edit/'.$row->getId().'" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' .
                '<a href="users/delete/'.$row->getId().'" id="'.$row->getId().'" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';
            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

}