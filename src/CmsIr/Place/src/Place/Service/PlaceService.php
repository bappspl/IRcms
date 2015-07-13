<?php

namespace CmsIr\Place\Service;

use CmsIr\System\Model\Status;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;

class PlaceService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findAsAssocArray()
    {
        $places = $this->getPlaceTable()->getAll();

        $assoc = array();

        /* @var $place \CmsIr\Place\Model\Place */
        foreach($places as $place){
            $id = $place->getId();
            $assoc[$id] = $place->getName();
        }

        return $assoc;
    }

    /**
     * @return \CmsIr\Place\Model\PlaceTable
     */
    public function getPlaceTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Place\Model\PlaceTable');
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
