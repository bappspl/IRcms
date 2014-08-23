<?php
namespace CmsIr\Dashboard;

// Add this for Table Date Gateway
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

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
                    __NAMESPACE__ => __DIR__ . '/src/Dashboard',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
            ),
        );
    }
}