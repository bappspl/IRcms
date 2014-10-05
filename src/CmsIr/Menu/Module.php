<?php
namespace CmsIr\Menu;

use CmsIr\Menu\Model\Menu;
use CmsIr\Menu\Model\MenuTable;
use CmsIr\Menu\Model\MenuNode;
use CmsIr\Menu\Model\MenuNodeTable;
use CmsIr\Menu\Model\MenuItem;
use CmsIr\Menu\Model\MenuItemTable;
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
                    __NAMESPACE__ => __DIR__ . '/src/Menu',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CmsIr\Menu\Model\MenuTable' =>  function($sm) {
                        $tableGateway = $sm->get('MenuTableGateway');
                        $table = new MenuTable($tableGateway);
                        return $table;
                    },
                'MenuTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Menu());
                        return new TableGateway('cms_menu_tree', $dbAdapter, null, $resultSetPrototype);
                    },
                'CmsIr\Menu\Model\MenuNodeTable' =>  function($sm) {
                        $tableGateway = $sm->get('MenuNodeTableGateway');
                        $table = new MenuNodeTable($tableGateway);
                        return $table;
                    },
                'MenuNodeTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new MenuNode());
                        return new TableGateway('cms_menu_node', $dbAdapter, null, $resultSetPrototype);
                    },
                'CmsIr\Menu\Model\MenuItemTable' =>  function($sm) {
                        $tableGateway = $sm->get('MenuItemTableGateway');
                        $table = new MenuItemTable($tableGateway);
                        return $table;
                    },
                'MenuItemTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new MenuItem());
                        return new TableGateway('cms_menu_item', $dbAdapter, null, $resultSetPrototype);
                    },
            ),
        );
    }
}