<?php

namespace CmsIr\Authorize;

// for Acl
use CmsIr\Authorize\Acl\Acl;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
	
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/Authorize',
                ),
            ),
        );
    }

	// FOR Authorization
	public function onBootstrap(\Zend\EventManager\EventInterface $e) // use it to attach event listeners
	{
		$application = $e->getApplication();
		$em = $application->getEventManager();
		$sm = $application->getServiceManager();

		$em->attach('route',
			function($e) use ($sm) {
					$sm->get('CmsIr\Authorize\Service\AuthorizeService')->onRoute($e);
			}
		);
	}
}