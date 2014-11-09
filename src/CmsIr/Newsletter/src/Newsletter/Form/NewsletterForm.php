<?php
namespace CmsIr\Newsletter\Form;

use CmsIr\Newsletter\Service\NewsletterService;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewsletterForm extends Form
{
    public function __construct(NewsletterService $service)
    {
        parent::__construct('Newsletter');
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
            'name' => 'subject',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'subject',
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Temat',
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
                'empty_option' => 'Wybierz status',
                'disable_inarray_validator' => true,
                'value_options' => array(
                    '4' => 'Szkic',
                    '3' => 'Wysłany'
                ),
            )
        ));

        $groups = $service->findSubscriberGroupsAsArray();
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
                'disable_inarray_validator' => true,
                'value_options' => $groups
            )
        ));

        $this->add(array(
            'name' => 'text',
            'attributes' => array(
                'id' => 'text',
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