<?php
namespace CmsIr\Users\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
// the object will be hydrated by Zend\Db\TableGateway\TableGateway
class Users
{
    protected $id;
    protected $name;
    protected $surname;
    protected $password;
    protected $filename;

    protected $email;
    protected $role;
    protected $active;
    protected $password_salt;
    protected $registration_date;
    protected $registration_token;
    protected $email_confirmed;

    protected $website_id;
    protected $dictionary_position_id;
    protected $dictionary_group_id;
    protected $position_description;

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

        $this->website_id = (isset($data['website_id'])) ? $data['website_id'] : null;
        $this->dictionary_position_id = (isset($data['dictionary_position_id'])) ? $data['dictionary_position_id'] : null;
        $this->dictionary_group_id = (isset($data['dictionary_group_id'])) ? $data['dictionary_group_id'] : null;
        $this->position_description = (isset($data['position_description'])) ? $data['position_description'] : null;
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
     * @param mixed $position_description
     */
    public function setPositionDescription($position_description)
    {
        $this->position_description = $position_description;
    }

    /**
     * @return mixed
     */
    public function getPositionDescription()
    {
        return $this->position_description;
    }

    /**
     * @param mixed $dictionary_group_id
     */
    public function setDictionaryGroupId($dictionary_group_id)
    {
        $this->dictionary_group_id = $dictionary_group_id;
    }

    /**
     * @return mixed
     */
    public function getDictionaryGroupId()
    {
        return $this->dictionary_group_id;
    }

    /**
     * @param mixed $dictionary_position_id
     */
    public function setDictionaryPositionId($dictionary_position_id)
    {
        $this->dictionary_position_id = $dictionary_position_id;
    }

    /**
     * @return mixed
     */
    public function getDictionaryPositionId()
    {
        return $this->dictionary_position_id;
    }

    /**
     * @param mixed $website_id
     */
    public function setWebsiteId($website_id)
    {
        $this->website_id = $website_id;
    }

    /**
     * @return mixed
     */
    public function getWebsiteId()
    {
        return $this->website_id;
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