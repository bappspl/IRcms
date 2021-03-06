<?php
namespace CmsIr\Authentication\Form;

use Zend\Form\Form;

class ForgottenPasswordForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');
		
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
                'class' => 'form-control no-border input-lg rounded',
                'placeholder' => 'Wprowadź email'
            ),
            'options' => array(
                'label' => 'E-mail',
            ),
        ));	
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Zmień hasło',
                'id' => 'submitbutton',
                'class' => 'btn btn-warning btn-lg btn-block'
            ),
        )); 
    }
}