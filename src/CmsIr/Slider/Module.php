<?php
namespace CmsIr\Slider;

use CmsIr\Slider\Model\Slider;
use CmsIr\Slider\Model\SliderItem;
use CmsIr\Slider\Model\SliderItemTable;
use CmsIr\Slider\Model\SliderTable;
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
                    __NAMESPACE__ => __DIR__ . '/src/Slider',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CmsIr\Slider\Model\SliderTable' =>  function($sm) {
                        $tableGateway = $sm->get('SliderTableGateway');
                        $table = new SliderTable($tableGateway);
                        $cacheAdapter = $sm->get('Zend\Cache\Storage\Filesystem');
                        $table->setCache($cacheAdapter);
                        return $table;
                    },
                'SliderTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Slider());
                        return new TableGateway('cms_slider', $dbAdapter, null, $resultSetPrototype);
                },
                'CmsIr\Slider\Model\SliderItemTable' =>  function($sm) {
                    $tableGateway = $sm->get('SliderItemTableGateway');
                    $table = new SliderItemTable($tableGateway);
                    return $table;
                },
                'SliderItemTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new SliderItem());
                    return new TableGateway('cms_slider_item', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}