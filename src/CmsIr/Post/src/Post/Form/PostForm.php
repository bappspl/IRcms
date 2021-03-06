<?php
namespace CmsIr\Post\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class PostForm extends Form
{
    public function __construct($tags)
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
            'name' => 'date',
            'attributes' => array(
                'id' => 'date',
                'type'  => 'text',
                'class'  => 'form-control datetimepicker',
            ),
            'options' => array(
                'label' => 'Data',
            ),
        ));

        $this->add(array(
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
                'name' => 'author_id',
            ),
            'options' => array(
                'label' => 'Autor',
                'value_options' => array(
                ),
                'disable_inarray_validator' => true,
            )
        ));

        $this->add(array(
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control select2',
                'name' => 'tag_id',
                'multiple' => 'multiple',
                'id' => 'tag_id',
            ),
            'options' => array(
                'label' => 'Tagi',
                'value_options' => $tags,
                'disable_inarray_validator' => true,
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
                'id' => 'filename_main'
            ),
        ));

        $this->add(array(
            'name' => 'filename_background',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'filename-background'
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

        $this->add(array(
            'name' => 'upload_background',
            'attributes' => array(
                'type'  => 'file',
                'id' => 'upload-background',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Upload',
            ),
        ));
    }
}