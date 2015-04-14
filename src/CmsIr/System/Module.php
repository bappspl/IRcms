<?php
namespace CmsIr\System;

use CmsIr\System\Model\Menu;
use CmsIr\System\Model\MenuTable;
use CmsIr\System\Model\Status;
use CmsIr\System\Model\StatusTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $application   = $e->getApplication();
        $sm = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $sharedManager->attach('Zend\Mvc\Application', 'render.error',
            function($e) use ($sm) {
                if ($e->getParam('exception')){
                    $sm->get('CmsIr\System\Logger\Logger')->logException($e->getParam('exception'));
                }
            }
        );

        $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error',
            function($e) use ($sm) {
                if ($e->getParam('exception')){
                    $sm->get('CmsIr\System\Logger\Logger')->logException($e->getParam('exception'));
                }
            }
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/System',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CmsIr\System\Model\StatusTable' =>  function($sm) {
                        $tableGateway = $sm->get('StatusTableGateway');
                        $table = new StatusTable($tableGateway);
                        return $table;
                    },
                'StatusTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Status());
                        return new TableGateway('cms_status', $dbAdapter, null, $resultSetPrototype);
                },
                'CmsIr\System\Model\MenuTable' =>  function($sm) {
                        $tableGateway = $sm->get('MenuTableGateway');
                        $table = new MenuTable($tableGateway);
                        return $table;
                    },
                'MenuTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Menu());
                        return new TableGateway('cms_backend_menu', $dbAdapter, null, $resultSetPrototype);
                },
                'Navigation' => 'CmsIr\System\Navigation\MyNavigationFactory'
            ),
            'invokables' => array(
                'menu' => 'CmsIr\System\Model\MenuTable'
            ),
            'initializers' => array(
                function ($instance, $sm) {
                    if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
                        $instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                    }
                }
            ),
        );
    }
}