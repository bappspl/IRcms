<?php
namespace CmsIr\Authentication\Form;

use Zend\Form\Form;

class AuthenticationForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Authentication');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control no-border input-lg rounded',
                'id' => 'login-log', 
                'placeholder' => 'Wprowadź email'
            ),
            'options' => array(
                'label' => 'Login',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'form-control no-border input-lg rounded',
                'id' => 'password-log', 
                'placeholder' => 'Wprowadź hasło' 
            ),
            'options' => array(
                'label' => 'Hasło',
            ),
        ));
        $this->add(array(
            'name' => 'rememberme',
			'type' => 'checkbox', 
            'id' => 'rememberme-log',
            'options' => array(
                'label' => 'Zapamiętaj mnie',
            ),
            'attributes' => array(
                'class' => 'i-yellow-flat'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Zaloguj',
                'id' => 'submitbutton',
                'class' => 'btn btn-warning btn-lg btn-perspective btn-block'
            ),
        ));
    }
}