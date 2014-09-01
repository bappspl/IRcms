<?php
namespace CmsIr\Users\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
// the object will be hydrated by Zend\Db\TableGateway\TableGateway
class Users
{
    public $id;
    public $name;
    public $surname;
    public $password;

    public $email;	
    public $role;
    public $active;
    public $password_salt;
    public $registration_date;
    public $registration_token;
    public $email_confirmed;	

    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->surname = (!empty($data['surname'])) ? $data['surname'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->role = (!empty($data['role'])) ? $data['role'] : null;
        $this->active = (isset($data['active'])) ? $data['active'] : null;
        $this->password_salt = (!empty($data['password_salt'])) ? $data['password_salt'] : null;
        $this->registration_date = (!empty($data['registration_date'])) ? $data['registration_date'] : null;
        $this->registration_token = (!empty($data['registration_token'])) ? $data['registration_token'] : null;
        $this->email_confirmed = (isset($data['email_confirmed'])) ? $data['email_confirmed'] : null;
    }	

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


	protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {

    }
}