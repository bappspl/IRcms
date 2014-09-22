<?php
namespace CmsIr\Authentication\Form;

use Zend\Form\Form;

class RegistrationForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control no-border input-lg rounded',
                'placeholder' => 'Wprowadź imię',
            ),
        ));

        $this->add(array(
            'name' => 'surname',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control no-border input-lg rounded',
                'placeholder' => 'Wprowadź nazwisko',
            ),
        ));
		
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
                'class' => 'form-control no-border input-lg rounded',
                'placeholder' => 'Wprowadź email',
            ),
        ));	
		
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'form-control no-border input-lg rounded',
                'placeholder' => 'Wprowadź hasło',
            ),
        ));
		
        $this->add(array(
            'name' => 'password_confirm',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'form-control no-border input-lg rounded',
                'placeholder' => 'Potwierdź hasło',
            ),
        ));
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Zarejestruj',
                'id' => 'submitbutton',
                'class' => 'btn btn-warning btn-lg btn-block'
            ),
        )); 
    }
}