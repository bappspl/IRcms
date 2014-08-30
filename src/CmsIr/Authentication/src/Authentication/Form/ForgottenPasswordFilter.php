<?php
namespace CmsIr\Authentication\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class ForgottenPasswordFilter extends InputFilter
{
	public function __construct($sm)
	{
        $this->add(array(
            'name'       => 'email',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
            ),
        ));
	}
}