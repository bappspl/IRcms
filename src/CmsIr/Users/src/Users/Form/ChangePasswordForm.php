<?php
namespace CmsIr\Users\Form;

use Zend\Form\Form;

class ChangePasswordForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Users');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'password_last',
            'attributes' => array(
                'type'  => 'password',
				'id' => 'password_last',
				'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Obecne hasło',
            ),
        ));
		$this->add(array(
            'name' => 'password_new',
            'attributes' => array(
                'type'  => 'password',
				'id' => 'password_new',
				'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Nowe hasło',
            ),
        ));

        $this->add(array(
            'name' => 'password_confirm',
            'attributes' => array(
                'type'  => 'password',
				'id' => 'password_confirm',
				'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Powtórzone nowe hasło',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Zapisz',
                'id' => 'submit',
                'class' => 'btn btn-primary pull-right'
            ),
        ));
    }
}