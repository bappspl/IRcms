<?php
namespace CmsIr\Newsletter\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
// the object will be hydrated by Zend\Db\TableGateway\TableGateway
class Subscriber
{
    protected $id;
    protected $email;
    protected $first_name;
    protected $confirmation_code;
    protected $groups;
    protected $status_id;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->email     = (!empty($data['email'])) ? $data['email'] : null;
        $this->first_name     = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->confirmation_code     = (!empty($data['confirmation_code'])) ? $data['confirmation_code'] : null;
        $this->groups     = (!empty($data['groups'])) ? $data['groups'] : null;
        $this->status_id     = (!empty($data['status_id'])) ? $data['status_id'] : null;
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
     * @param mixed $confirmation_code
     */
    public function setConfirmationCode($confirmation_code)
    {
        $this->confirmation_code = $confirmation_code;
    }

    /**
     * @return mixed
     */
    public function getConfirmationCode()
    {
        return $this->confirmation_code;
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
     * @param mixed $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
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
     * @param mixed $status_id
     */
    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    /**
     * @return mixed
     */
    public function getStatusId()
    {
        return $this->status_id;
    }

}