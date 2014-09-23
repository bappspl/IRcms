<?php
namespace CmsIr\Slider\Form;

use Zend\Form\Form;

class SliderForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Slider');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
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
                'id' => 'submitbutton',
                'class' => 'btn btn-success'
            ),
        ));
    }
}