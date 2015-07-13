<?php

namespace CmsIr\Meta\View\Helper;

use CmsIr\Meta\Form\MetaForm;
use CmsIr\Meta\Model\Meta;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;

class MetaHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function getMetaForm($entity)
    {
        $form = new MetaForm();

        if($entity)
        {
            $type = get_class($entity);
            $explode = explode('\\', $type);
            $entityType = end($explode);
            $entityId = $entity->getId();

            /* @var $meta Meta */
            $meta = $this->getMetaTable()->getOneBy(array('entity_type' => $entityType, 'entity_id' => $entityId));
            $form->bind($meta);
        }
        return $form;
    }

    public function setMeta($entityType, $entityId)
    {
        /* @var $meta Meta */
        $meta = $this->getMetaTable()->getOneBy(array('entity_type' => $entityType, 'entity_id' => $entityId));

        $viewHelperManager = $this->serviceLocator->getServiceLocator()->get('viewHelperManager');

        $viewHelperManager->get('headTitle')->setSeparator(' - ')->append($meta->getTitle());
        $viewHelperManager->get('headMeta')->appendName('keywords', $meta->getKeywords());
        $viewHelperManager->get('headMeta')->appendName('description', $meta->getDescription());
    }

    /**
     * @return \CmsIr\Meta\Model\MetaTable
     */
    public function getMetaTable()
    {
        return $this->serviceLocator->getServiceLocator()->get('CmsIr\Meta\Model\MetaTable');
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}