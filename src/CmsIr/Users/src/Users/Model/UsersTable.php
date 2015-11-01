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


}