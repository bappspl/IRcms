<?php

namespace CmsIr\System\Service;

use CmsIr\System\Model\Status;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;

class StatusService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findAsAssocArray()
    {
        $statuses = $this->getStatusTable()->getAll();

        $assoc = array();

        /* @var $status Status */
        foreach($statuses as $status){
            $id = $status->getId();
            $assoc[$id] = $status->getName() == 'Active' ? 'Aktywny' : 'Nieaktywny';
        }

        return $assoc;
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
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
