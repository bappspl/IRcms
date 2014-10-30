<?php
namespace CmsIr\Newsletter\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class SubscriberFormFilter extends InputFilter
{
	public function __construct($sm)
	{
        $this->add(array(
            'name'       => 'email',
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
                ),
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'message' =>  'Błedny format maila!'
                    )
                ),
            ),
        ));
	}
}