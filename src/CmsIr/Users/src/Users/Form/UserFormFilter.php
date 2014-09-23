<?php
namespace CmsIr\Users\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\AbstractValidator;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;

class UserFormFilter extends InputFilter
{
	public function __construct($sm)
	{
        $this->add(array(
            'name'     => 'name',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Uzupełnij pole!'
                        )
                    )
                )
            ),
        ));

        $this->add(array(
            'name'     => 'surname',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Uzupełnij pole!'
                        )
                    )
                )
            ),
        ));

        $this->add(array(
            'name'       => 'email',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Uzupełnij pole!'
                        )
                    ),
                ),
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'message' =>  'Błedny format maila!'
                    )
                ),
                array(
                    'name'		=> 'Zend\Validator\Db\NoRecordExists',
                    'options' => array(
                        'table'   => 'cms_users',
                        'field'   => 'email',
                        'adapter' => $sm->get('Zend\Db\Adapter\Adapter'),
                        'message' =>  'Podany mail jest już zarejestrowany!'
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'upload',
            'required'   => false,
        ));

	}
}