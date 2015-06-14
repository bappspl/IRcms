<?php
namespace CmsIr\Page\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class PageForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Page');
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
                    '2' => 'Nieaktywna',
                    '1' => 'Aktywna'
                ),
            )
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
            'name' => 'subtitle',
            'attributes' => array(
                'id' => 'subtitle',
                'type'  => 'text',
                'placeholder' => 'Wprowadź podtytuł'
            ),
            'options' => array(
                'label' => 'Podtytuł',
            ),
        ));

        $this->add(array(
            'name' => 'url',
            'attributes' => array(
                'id' => 'url',
                'type'  => 'text',
                'placeholder' => 'Wprowadź Url'
            ),
            'options' => array(
                'label' => 'Url',
            ),
        ));

        $this->add(array(
            'name' => 'content',
            'attributes' => array(
                'id' => 'content',
                'type'  => 'textarea',
                'placeholder' => 'Wprowadź zawartość strony',
                'class' => 'summernote-lg',
            ),
            'options' => array(
                'label' => 'Zawartość strony',
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

        $this->add(array(
            'name' => 'filename',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'filename'
            ),
        ));

        $this->add(array(
            'name' => 'filename_main',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'filename-main'
            ),
        ));

        $this->add(array(
            'name' => 'upload',
            'attributes' => array(
                'type'  => 'file',
                'id' => 'upload',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Upload',
            ),
        ));

        $this->add(array(
            'name' => 'upload_main',
            'attributes' => array(
                'type'  => 'file',
                'id' => 'upload-main',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Upload',
            ),
        ));
    }
}