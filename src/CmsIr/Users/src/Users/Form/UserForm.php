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
            'name' => 'filename',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'filename',
                'class' => 'form-control'
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