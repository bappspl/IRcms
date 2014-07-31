<?php
namespace CmsIr\Authentication\Form;

use Zend\Form\Form;

class AuthenticationForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('easy');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'login',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'text',
                'id' => 'login-log', 
                'placeholder' => 'Wprowadź login'
            ),
            'options' => array(
                'label' => 'Login',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'text', 
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
        ));			
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Login',
                'id' => 'submitbutton',
            ),
        ));
    }
}