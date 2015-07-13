<?php
namespace CmsIr\Meta\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class MetaForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Meta');
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
            'name' => 'title',
            'attributes' => array(
                'id' => 'title',
                'type'  => 'text',
                'placeholder' => 'Wprowadź tytuł'
            ),
            'options' => array(
                'label' => 'Tytuł',
            ),
        ));

        $this->add(array(
            'name' => 'keywords',
            'attributes' => array(
                'id' => 'keywords',
                'type'  => 'text',
                'placeholder' => 'Wprowadź słowa kluczowe'
            ),
            'options' => array(
                'label' => 'Słowa kluczowe',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'id' => 'description',
                'type'  => 'textarea',
                'placeholder' => 'Wprowadź opis'
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