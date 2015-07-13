<?php

namespace CmsIr\Meta\Service;

use CmsIr\File\Model\File;
use CmsIr\Meta\Model\Meta;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MetaService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function saveMeta($entityType, $entityId, $postData)
    {
        /* @var $meta Meta */
        $meta = $this->getMetaTable()->getOneBy(array('entity_type' => $entityType, 'entity_id' => $entityId));

        if($meta)
        {
            $meta->setTitle($postData->title);
            $meta->setKeywords($postData->keywords);
            $meta->setDescription($postData->description);
        } else
        {
            $meta = new Meta();
            $meta->setTitle($postData->title);
            $meta->setKeywords($postData->keywords);
            $meta->setDescription($postData->description);
            $meta->setEntityId($entityId);
            $meta->setEntityType($entityType);
        }

        $this->getMetaTable()->save($meta);
    }
    /**
     * @return \CmsIr\Meta\Model\MetaTable
     */
    public function getMetaTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Meta\Model\MetaTable');
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
