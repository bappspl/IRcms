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
    public $filename;

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
        $this->filename = (!empty($data['filename'])) ? $data['filename'] : null;

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

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email_confirmed
     */
    public function setEmailConfirmed($email_confirmed)
    {
        $this->email_confirmed = $email_confirmed;
    }

    /**
     * @return mixed
     */
    public function getEmailConfirmed()
    {
        return $this->email_confirmed;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password_salt
     */
    public function setPasswordSalt($password_salt)
    {
        $this->password_salt = $password_salt;
    }

    /**
     * @return mixed
     */
    public function getPasswordSalt()
    {
        return $this->password_salt;
    }

    /**
     * @param mixed $registration_date
     */
    public function setRegistrationDate($registration_date)
    {
        $this->registration_date = $registration_date;
    }

    /**
     * @return mixed
     */
    public function getRegistrationDate()
    {
        return $this->registration_date;
    }

    /**
     * @param mixed $registration_token
     */
    public function setRegistrationToken($registration_token)
    {
        $this->registration_token = $registration_token;
    }

    /**
     * @return mixed
     */
    public function getRegistrationToken()
    {
        return $this->registration_token;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }


}