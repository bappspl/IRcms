<?php

namespace CmsIr\File\Service;

use CmsIr\File\Model\File;
use CmsIr\File\Model\Gallery;
use CmsIr\System\Model\Block;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GalleryService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findAll($langId)
    {
        $galleries = $this->getGalleryTable()->getBy(array('status_id' => 1));

        if(!$galleries) {
            return null;
        }

        /* @var $gallery Gallery */
        foreach ($galleries as $gallery) {
            $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Gallery', 'language_id' => $langId, 'entity_id' => $gallery->getId()));

            /* @var $block Block */
            foreach($blocks as $block) {
                $fieldName = $block->getName();

                switch ($fieldName) {
                    case 'title':
                        $gallery->setTitle($block->getValue());
                        break;
                }
            }

            $files = $this->getFileTable()->getBy(array('entity_type' => 'gallery', 'entity_id' => $gallery->getId()));
            $gallery->setFiles($files);

            $category = $this->getCategoryTable()->getOneBy(array('id' => $gallery->getCategoryId()));
            $gallery->setCategory($category);
        }

        return $galleries;
    }

    public function findOne($langId, $id)
    {
        /* @var $gallery Gallery */
        $gallery = $this->getGalleryTable()->getOneBy(array('id' => $id));

        $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Gallery', 'language_id' => $langId, 'entity_id' => $gallery->getId()));

        /* @var $block Block */
        foreach($blocks as $block) {
            $fieldName = $block->getName();

            switch ($fieldName) {
                case 'title':
                    $gallery->setTitle($block->getValue());
                    break;
            }
        }

        $files = $this->getFileTable()->getBy(array('entity_type' => 'gallery', 'entity_id' => $gallery->getId()));
        $gallery->setFiles($files);

        $category = $this->getCategoryTable()->getOneBy(array('id' => $gallery->getCategoryId()));
        $gallery->setCategory($category);


        return $gallery;
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
    }

    /**
     * @return \CmsIr\File\Model\GalleryTable
     */
    public function getGalleryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\GalleryTable');
    }

    /**
     * @return \CmsIr\Category\Model\CategoryTable
     */
    public function getCategoryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Category\Model\CategoryTable');
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

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
    }
}
