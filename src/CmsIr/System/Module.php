<?php
namespace CmsIr\System;

use CmsIr\System\Model\Block;
use CmsIr\System\Model\BlockTable;
use CmsIr\System\Model\Language;
use CmsIr\System\Model\LanguageTable;
use CmsIr\System\Model\LogEvent;
use CmsIr\System\Model\LogEventTable;
use CmsIr\System\Model\MailConfig;
use CmsIr\System\Model\MailConfigTable;
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
                'CmsIr\System\Model\MailConfigTable' =>  function($sm) {
                    $tableGateway = $sm->get('MailConfigTableGateway');
                    $table = new MailConfigTable($tableGateway);
                    return $table;
                },
                'MailConfigTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new MailConfig());
                    return new TableGateway('cms_mail_config', $dbAdapter, null, $resultSetPrototype);
                },
                'CmsIr\System\Model\LogEventTable' =>  function($sm) {
                    $tableGateway = $sm->get('LogEventTableGateway');
                    $table = new LogEventTable($tableGateway);
                    return $table;
                },
                'LogEventTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new LogEvent());
                    return new TableGateway('cms_log_event', $dbAdapter, null, $resultSetPrototype);
                },
                'CmsIr\System\Model\LanguageTable' =>  function($sm) {
                    $tableGateway = $sm->get('LanguageTableGateway');
                    $table = new LanguageTable($tableGateway);
                    return $table;
                },
                'LanguageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Language());
                    return new TableGateway('cms_language', $dbAdapter, null, $resultSetPrototype);
                },
                'CmsIr\System\Model\BlockTable' =>  function($sm) {
                    $tableGateway = $sm->get('BlockTableGateway');
                    $table = new BlockTable($tableGateway);
                    return $table;
                },
                'BlockTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Block());
                    return new TableGateway('cms_language', $dbAdapter, null, $resultSetPrototype);
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