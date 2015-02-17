<?php
namespace CmsIr\Users\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\AbstractValidator;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;

class ChangePasswordFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
			'name'     => 'password_last',
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
                    ),
                ),
			),
		));

        $this->add(array(
            'name'     => 'password_new',
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
                    ),
                ),
            ),
        ));

		$this->add(array(
			'name'     => 'password_confirm',
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
                    ),
                    'break_chain_on_failure' => true,
                ),
                array(
                    'name'    => 'Identical',
                    'options' => array(
                        'token' => 'password_new',
                        'message' =>  'Podane hasła muszą być takie same!'
                    ),
                ),
			),
		));
	}
}