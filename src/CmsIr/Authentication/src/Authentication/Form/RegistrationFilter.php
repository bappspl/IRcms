<?php
namespace CmsIr\Authentication\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\AbstractValidator;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;

class RegistrationFilter extends InputFilter
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
                    //'break_chain_on_failure' => true,
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
			'name'     => 'password',
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
                        'token' => 'password',
                        'message' =>  'Podane hasła muszą być takie same!'
                    ),
                ),
			),
		));
	}
}