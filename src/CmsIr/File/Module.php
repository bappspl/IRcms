<?php
namespace CmsIr\File;

use CmsIr\File\Model\File;
use CmsIr\File\Model\FileTable;
use CmsIr\File\Model\Gallery;
use CmsIr\File\Model\GalleryTable;
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
                    __NAMESPACE__ => __DIR__ . '/src/File',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CmsIr\File\Model\FileTable' =>  function($sm) {
                    $tableGateway = $sm->get('FileTableGateway');
                    $table = new FileTable($tableGateway);
                    return $table;
                },
                'FileTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new File());
                    return new TableGateway('cms_file', $dbAdapter, null, $resultSetPrototype);
                },
                'CmsIr\File\Model\GalleryTable' =>  function($sm) {
                    $tableGateway = $sm->get('GalleryTableGateway');
                    $table = new GalleryTable($tableGateway);
                    return $table;
                },
                'GalleryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Gallery());
                    return new TableGateway('cms_gallery', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}