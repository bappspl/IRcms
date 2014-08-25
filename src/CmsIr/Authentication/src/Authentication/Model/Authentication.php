<?php
namespace CmsIr\Authentication\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
// the object will be hydrated by Zend\Db\TableGateway\TableGateway
class Authentication implements InputFilterAwareInterface
{
    public $id;
//    public $login;
    public $name;
    public $surname;
    public $password;

    public $email;	
    public $role;
    //public $usrl_id;	
    //public $lng_id;	
    public $active;	
    //public $usr_question;	
    //public $usr_answer;	
    //public $usr_picture;	
    public $password_salt;
//    public $registration_date;
//    public $registration_token;
    public $email_confirmed;	

	// Hydration
	// ArrayObject, or at least implement exchangeArray. For Zend\Db\ResultSet\ResultSet to work
    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
//        $this->login = (!empty($data['login'])) ? $data['login'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->surname = (!empty($data['surname'])) ? $data['surname'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->role = (!empty($data['role'])) ? $data['role'] : null;
        //$this->lng_id = (!empty($data['lng_id'])) ? $data['lng_id'] : null;
        $this->active = (isset($data['active'])) ? $data['active'] : null;
        //$this->usr_question = (!empty($data['usr_question'])) ? $data['usr_question'] : null;
       // $this->usr_answer = (!empty($data['usr_answer'])) ? $data['usr_answer'] : null;
       // $this->usr_picture = (!empty($data['usr_picture'])) ? $data['usr_picture'] : null;
        $this->password_salt = (!empty($data['password_salt'])) ? $data['password_salt'] : null;
//        $this->registration_date = (!empty($data['registration_date'])) ? $data['registration_date'] : null;
//        $this->registration_token = (!empty($data['registration_token'])) ? $data['registration_token'] : null;
        $this->email_confirmed = (isset($data['email_confirmed'])) ? $data['email_confirmed'] : null;
    }	

	// Extraction. The Registration from the tutorial works even without it.
	// The standard Hydrator of the Form expects getArrayCopy to be able to bind
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
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'password',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }	
}