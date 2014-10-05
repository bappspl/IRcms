<?php
namespace CmsIr\Slider\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class SliderFormFilter extends InputFilter
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
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Uzupełnij pole!'
                        )
                    )
                ),
//                array(
//                    'name'		=> 'Zend\Validator\Db\NoRecordExists',
//                    'options' => array(
//                        'table'   => 'cms_slider',
//                        'field'   => 'name',
//                        'adapter' => $sm->get('Zend\Db\Adapter\Adapter'),
//                        'message' =>  'Slider o podanej nazwie już istnieje!',
//                    ),
//                ),
            ),
        ));
	}
}