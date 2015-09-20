<?php

namespace CmsIr\Category\Service;

use CmsIr\Category\Model\Category;
use CmsIr\System\Model\Block;
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

    public function findAllWithBlocks($langId)
    {
        $categories = $this->getCategoryTable()->getAll();

        /* @var $category Category */
        foreach($categories as $category)
        {
            $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Category', 'language_id' => $langId, 'entity_id' => $category->getId()));
            $category->setBlocks($blocks);

            /* @var $block Block */
            foreach($blocks as $block)
            {
                $fieldName = $block->getName();

                switch ($fieldName)
                {
                    case 'title':
                        $category->setTitle($block->getValue());
                        break;
                    case 'content':
                        $category->setContent($block->getValue());
                        break;
                }
            }

            $files = $this->getFileTable()->getBy(array('entity_type' => 'Category', 'entity_id' => $category->getId()));
            $category->setFiles($files);
        }

        return $categories;
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
