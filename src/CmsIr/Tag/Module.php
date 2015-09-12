<?php
namespace CmsIr\Tag;

use CmsIr\Tag\Model\Tag;
use CmsIr\Tag\Model\TagEntity;
use CmsIr\Tag\Model\TagEntityTable;
use CmsIr\Tag\Model\TagTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $viewModel = $e->getViewModel();

        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $loggedUser = $auth->getIdentity();
            $viewModel->loggedUser = $loggedUser;
        }
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
                    __NAMESPACE__ => __DIR__ . '/src/Tag',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CmsIr\Tag\Model\TagTable' =>  function($sm) {
                    $tableGateway = $sm->get('TagTableGateway');
                    $table = new TagTable($tableGateway);
                    return $table;
                },
                'TagTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tag());
                    return new TableGateway('cms_tag', $dbAdapter, null, $resultSetPrototype);
                },
                'CmsIr\Tag\Model\TagEntityTable' =>  function($sm) {
                    $tableGateway = $sm->get('TagEntityTableGateway');
                    $table = new TagEntityTable($tableGateway);
                    return $table;
                },
                'TagEntityTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new TagEntity());
                    return new TableGateway('cms_tag_entity', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}