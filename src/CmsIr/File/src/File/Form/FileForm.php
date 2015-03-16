<?php
namespace CmsIr\File\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class FileForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('File');
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
            'name' => 'name',
            'attributes' => array(
                'id' => 'name',
                'type'  => 'text',
                'placeholder' => 'Wprowadź nazwę'
            ),
            'options' => array(
                'label' => 'Nazwa',
            ),
        ));

        $this->add(array(
            'name' => 'first',
            'attributes' => array(
                'id' => 'first',
                'type'  => 'text',
                'placeholder' => 'Wprowadź cenę'
            ),
            'options' => array(
                'label' => 'Cena I',
            ),
        ));

        $this->add(array(
            'name' => 'second',
            'attributes' => array(
                'id' => 'second',
                'type'  => 'text',
                'placeholder' => 'Wprowadź cenę'
            ),
            'options' => array(
                'label' => 'Cena II',
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