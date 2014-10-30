<?php
namespace CmsIr\Newsletter\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class SubscriberForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Subscriber');
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
            'name' => 'email',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'email',
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

        $this->add(array(
            'name' => 'first_name',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'first_name',
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Imię',
            ),
        ));

        $this->add(array(
            'name' => 'confirmation_code',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'confirmation_code',
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Kod potwierdzający',
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
                    '6' => 'Niepotwierdzony',
                    '5' => 'Potwierdzony'
                ),
            )
        ));

        $this->add(array(
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control chosen-select',
                'name' => 'groups',
                'multiple' => 'multiple',
                'data-placeholder' => 'Wybierz grupy'
            ),
            'options' => array(
                'label' => 'Grupy',
            )
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