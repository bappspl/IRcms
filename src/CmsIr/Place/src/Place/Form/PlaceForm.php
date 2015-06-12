<?php
namespace CmsIr\Place\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class PlaceForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Place');
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
            'name' => 'latitude',
            'attributes' => array(
                'id' => 'latitude',
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Szerokość',
            ),
        ));

        $this->add(array(
            'name' => 'longitude',
            'attributes' => array(
                'id' => 'longitude',
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Długość',
            ),
        ));

        $this->add(array(
            'name' => 'country',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'country'
            ),
        ));

        $this->add(array(
            'name' => 'region',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'region'
            ),
        ));

        $this->add(array(
            'name' => 'city',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'city'
            ),
        ));

        $this->add(array(
            'name' => 'street',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'street'
            ),
        ));

        $this->add(array(
            'name' => 'street_number',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'street_number'
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