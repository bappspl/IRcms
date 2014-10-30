<?php
namespace CmsIr\Newsletter\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class SubscriberGroupForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('SubscriberGroup');
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
            ),
            'options' => array(
                'label' => 'Nazwa',
            ),
        ));

        $this->add(array(
            'name' => 'slug',
            'attributes' => array(
                'id' => 'slug',
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Nazwa systemowa',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'id' => 'description',
                'class' => 'summernote-sm',
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Opis',
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