<?php
namespace CmsIr\Newsletter\Form;

use CmsIr\Newsletter\Model\SubscriberGroupTable;
use Zend\Form\Fieldset;

class NewsletterFieldset extends Fieldset
{
    public function __construct(SubscriberGroupTable $subscriberGroupTable)
    {
        $subscriberGroups = $subscriberGroupTable->getAll();
        $tmpArrayGroups = array();
        foreach ($subscriberGroups as $keyGroup => $group) {
            $tmp = array(
                'value' => $group->getId(),
                'label' => $group->getName()
            );
            array_push($tmpArrayGroups, $tmp);
        }

        $this->add(array(
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control chosen-select',
                'name' => 'groups',
                'multiple' => 'multiple',
                'data-placeholder' => 'Wybierz grupy'
            ),
            'options' => array(
                'label' => 'Grupy',
                'disable_inarray_validator' => true,
                'value_options' => $tmpArrayGroups
            ),

        ));
    }
}