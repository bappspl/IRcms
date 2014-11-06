<?php
namespace CmsIr\Post\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class PostForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Post');
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
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
                'name' => 'status_id',
            ),
            'options' => array(
                'label' => 'Status',
                'value_options' => array(
                    '2' => 'Nieaktywny',
                    '1' => 'Aktywny'
                ),
            )
        ));

        $this->add(array(
            'name' => 'url',
            'attributes' => array(
                'id' => 'url',
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Url',
            ),
        ));

        $this->add(array(
            'name' => 'text',
            'attributes' => array(
                'id' => 'text',
                'class' => 'summernote-lg',
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