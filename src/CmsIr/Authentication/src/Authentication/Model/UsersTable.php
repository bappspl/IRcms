<?php
namespace CmsIr\Authentication\Model;

use Zend\Db\TableGateway\TableGateway;

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

	public function getUserByToken($token)
    {
        $rowset = $this->tableGateway->select(array('registration_token' => $token));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $token");
        }
        return $row;
    }
	
    public function activateUser($id)
    {
		$data['active'] = 1;
		$data['email_confirmed'] = 1;
		$this->tableGateway->update($data, array('id' => (int)$id));
    }	

    public function getUserByEmail($email)
    {
        $rowset = $this->tableGateway->select(array('email' => $email));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $email");
        }
        return $row;
    }

    public function changePassword($id, $password)
    {
		$data['password'] = $password;
		$this->tableGateway->update($data, array('id' => (int)$id));
    }
	
    public function saveUser(Authentication $auth)
    {
        $data = array(
            'login' 				=> $auth->login,
            'password'  		    => $auth->password,
            'email'  			    => $auth->email,
            'active'  		    	=> $auth->active,
            'password_salt' 	    => $auth->password_salt,
			'email_confirmed'	    => $auth->email_confirmed,
        );

        $id = (int)$auth->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form $id does not exist');
            }
        }
    }
	
    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function findLogin($login)
    {
        $rowset = $this->tableGateway->select(array('login' => $login));
        $row = $rowset->current();
        if (!$row) {
            return true;
        } else {
            return false;
        }
    }

    public function findEmail($email)
    {
        $rowset = $this->tableGateway->select(array('email' => $email));
        $row = $rowset->current();
        if (!$row) {
            return true;
        } else {
            return false;
        }
    }
}