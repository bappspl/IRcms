<?php

namespace CmsIr\File\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FileService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findAllByCategoryAndWebsiteId($category, $websiteId)
    {
        $prices = $this->getFileTable()->getBy(array('category' => $category, 'website_id' => $websiteId));

        return $prices;
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
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
