<?php
namespace CmsIr\Authentication\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class AuthenticationFormFilter extends InputFilter
{
	public function __construct($sm)
	{
        $this->add(array(
            'name'       => 'name',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 100,
                    ),
                ),
                array(
                    'name'		=> 'Zend\Validator\Db\NoRecordExists',
                    'options' => array(
                        'table'   => 'cms_slider',
                        'field'   => 'name',
                        'adapter' => $sm->get('Zend\Db\Adapter\Adapter'),
                        'message' =>  'Slider o podanej nazwie już istnieje!'
                    ),
                ),
            ),
        ));
	}
}