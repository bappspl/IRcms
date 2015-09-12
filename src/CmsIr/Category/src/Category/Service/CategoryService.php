<?php

namespace CmsIr\Category\Service;

use CmsIr\Category\Model\Category;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CategoryService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findAsAssocArray()
    {
        /* @var $category Category */
        $categories = $this->getCategoryTable()->getAll();

        $categoryArray = array();

        foreach($categories as $category)
        {
            $categoryArray[$category->getId()] = $category->getName();
        }

        return $categoryArray;
    }

    /**
     * @return \CmsIr\System\Model\LanguageTable
     */
    public function getLanguageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\LanguageTable');
    }

    /**
     * @return \CmsIr\Category\Model\CategoryTable
     */
    public function getCategoryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Category\Model\CategoryTable');
    }

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
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
