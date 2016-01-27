<?php

namespace CmsIr\Authorize\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthorizeService implements FactoryInterface {
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new \CmsIr\Authorize\Service\AuthorizeService();
        return $service;
    }

}
 