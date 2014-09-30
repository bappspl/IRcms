<?php
namespace CmsIr\System;

use CmsIr\System\Model\Status;
use CmsIr\System\Model\StatusTable;
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
            ),
        );
    }
}