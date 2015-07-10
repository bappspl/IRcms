<?php
namespace CmsIr\Page\Form;

use CmsIr\System\Entity\Status;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class PageForm extends Form
{
    public function __construct($statuses)
    {
        parent::__construct('Page');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());

        $id = new Element\Hidden('id');
        $id->setAttribute('id', 'id');
        $this->add($id);

        $statusesToForm = array();

        /* @var $s Status */
        foreach($statuses as $s)
        {
            $name = $s->getSlug();

            switch ($name)
            {
                case 'active':
                    $name = 'Aktywna';
                    break;
                case 'inactive':
                    $name = 'Nieaktywna';
                    break;
            }

            $statusesToForm[$s->getId()] = $name;
        }

        $status = new Element\Select('status');
        $status->setAttributes(array(
            'class' => 'form-control'
        ));
        $status->setLabel('Status');
        $status->setValueOptions($statusesToForm);
        $this->add($status);

        $name = new Element\Text('name');
        $name->setAttributes(array(
            'id' => 'name',
            'placeholder' => 'Wprowadź nazwę'
        ));
        $name->setLabel('Nazwa');
        $this->add($name);

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
            'name' => 'files',
            'attributes' => array(
                'type'  => 'hidden',
                'id' => 'files'
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