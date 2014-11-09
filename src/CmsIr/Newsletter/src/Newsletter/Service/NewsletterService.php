<?php

namespace CmsIr\Newsletter\Service;

use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;

class NewsletterService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findSubscriberGroupsAsArray()
    {
        $groups = $this->getSubscriberGroupTable()->getAll();

        $assoc = array();

        foreach($groups as $group){
            $id = $group->getId();
            $assoc[$id] = $group->getName();
        }

        return $assoc;
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberGroupTable
     */
    public function getSubscriberGroupTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberGroupTable');
    }

    /**
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}
