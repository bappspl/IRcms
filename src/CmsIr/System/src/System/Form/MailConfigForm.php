<?php
namespace CmsIr\System\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class MailConfigForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('MailConfig');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'id'
            ),
        ));

        $this->add(array(
            'name' => 'host',
            'attributes' => array(
                'id' => 'name',
                'type'  => 'text',
                'placeholder' => 'Wprowadź host'
            ),
            'options' => array(
                'label' => 'Host',
            ),
        ));

        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'id' => 'username',
                'type'  => 'text',
                'placeholder' => 'Wprowadź login'
            ),
            'options' => array(
                'label' => 'Login',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'id' => 'password',
                'type'  => 'password',
                'placeholder' => 'Wprowadź hasło'
            ),
            'options' => array(
                'label' => 'Hasło',
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