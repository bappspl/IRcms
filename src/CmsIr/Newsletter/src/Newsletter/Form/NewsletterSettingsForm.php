<?php
namespace CmsIr\Newsletter\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewsletterSettingsForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('NewsletterSettings');
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
            'name' => 'sender_email',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'sender_email',
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Email wysyłający',
            ),
        ));

        $this->add(array(
            'name' => 'sender',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'sender',
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Nadawca',
            ),
        ));


        $this->add(array(
            'name' => 'footer',
            'attributes' => array(
                'id' => 'footer',
                'class' => 'summernote-lg',
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Treść',
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