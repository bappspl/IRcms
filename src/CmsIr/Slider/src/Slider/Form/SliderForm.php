<?php
namespace CmsIr\Slider\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class SliderForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Slider');
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
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
                'name' => 'status_id',
            ),
            'options' => array(
                'label' => 'Status',
                'value_options' => array(
                     '1' => 'Aktywny',
                     '2' => 'Nieaktywny'
                ),
            )
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'id' => 'name',
                'type'  => 'text',
//                'class' => 'form-control',
                'placeholder' => 'Wprowadź nazwę'
            ),
            'options' => array(
                'label' => 'Nazwa',
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