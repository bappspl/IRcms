<?php
namespace CmsIr\Users\Form;

use Zend\Form\Form;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Users');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'id'
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'password'
            ),
        ));
        $this->add(array(
            'name' => 'password_salt',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'password_salt'
            ),
        ));

        $this->add(array(
            'name' => 'filename',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'filename'
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
				'id' => 'name',
				'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Imię',
            ),
        ));
		$this->add(array(
            'name' => 'surname',
            'attributes' => array(
                'type'  => 'text',
				'id' => 'surname',
				'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Nazwisko',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
				'id' => 'email',
				'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'E-mail',
            ),
        ));

        $this->add(array(
            'type' => 'select',
            'name' => 'role',
            'attributes' => array(
                'class' => 'form-control',
                'name' => 'role',
            ),
            'options' => array(
                'label' => 'Rola',
            )
        ));

        $this->add(array(
            'name' => 'upload',
            'attributes' => array(
                'type'  => 'file',
                'id' => 'upload',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'E-mail',
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