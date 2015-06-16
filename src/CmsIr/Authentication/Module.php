<?php
namespace CmsIr\Authentication;

// Add this for Table Date Gateway
use CmsIr\Authentication\Model\Authentication;
use CmsIr\Authentication\Model\UsersTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

// Add this for SMTP transport
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

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
                    __NAMESPACE__ => __DIR__ . '/src/Authentication',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CmsIr\Authentication\Model\UsersTable' =>  function($sm) {
                    $tableGateway = $sm->get('AuthTableGateway');
                    $table = new UsersTable($tableGateway);
                    return $table;
                },
                'AuthTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Authentication());
                    return new TableGateway('cms_users', $dbAdapter, null, $resultSetPrototype);
                },
                'mail.transport' => function (ServiceManager $serviceManager) {
                    $config = $serviceManager->get('Config');
                    $transport = new Smtp();
                    $transport->setOptions(new SmtpOptions($config['mail']['transport']['options']));
                    return $transport;
                },
            ),
        );
    }		
}