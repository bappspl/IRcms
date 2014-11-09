<?php
namespace CmsIr\Newsletter;

// Add this for Table Date Gateway
use CmsIr\Newsletter\Model\Newsletter;
use CmsIr\Newsletter\Model\NewsletterTable;
use CmsIr\Newsletter\Model\NewsletterSettings;
use CmsIr\Newsletter\Model\NewsletterSettingsTable;
use CmsIr\Newsletter\Model\SubscriberGroup;
use CmsIr\Newsletter\Model\SubscriberGroupTable;
use CmsIr\Newsletter\Model\Subscriber;
use CmsIr\Newsletter\Model\SubscriberTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;

class Module implements FormElementProviderInterface
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
                    __NAMESPACE__ => __DIR__ . '/src/Newsletter',
                ),
            ),
        );
    }

    public function getFormElementConfig()
    {
        return array(
            'factories' => array(
                'newsletterForm' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $service = $serviceLocator->get('CmsIr\Newsletter\Service\NewsletterService');
                    $form = new \CmsIr\Newsletter\Form\NewsletterForm($service);
                    return $form;
                },
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CmsIr\Newsletter\Model\NewsletterTable' =>  function($sm) {
                    $tableGateway = $sm->get('NewsletterTableGateway');
                    $table = new NewsletterTable($tableGateway);
                    return $table;
                },
                'NewsletterTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Newsletter());
                    return new TableGateway('cms_newsletter', $dbAdapter, null, $resultSetPrototype);
                },
                'CmsIr\Newsletter\Model\SubscriberGroupTable' =>  function($sm) {
                        $tableGateway = $sm->get('SubscriberGroupTableGateway');
                        $table = new SubscriberGroupTable($tableGateway);
                        return $table;
                    },
                'SubscriberGroupTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new SubscriberGroup());
                        return new TableGateway('cms_subscriber_group', $dbAdapter, null, $resultSetPrototype);
                    },
                'CmsIr\Newsletter\Model\SubscriberTable' =>  function($sm) {
                        $tableGateway = $sm->get('SubscriberTableGateway');
                        $table = new SubscriberTable($tableGateway);
                        return $table;
                    },
                'SubscriberTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new Subscriber());
                        return new TableGateway('cms_subscriber', $dbAdapter, null, $resultSetPrototype);
                    },
                'CmsIr\Newsletter\Model\NewsletterSettingsTable' =>  function($sm) {
                        $tableGateway = $sm->get('NewsletterSettingsTableGateway');
                        $table = new NewsletterSettingsTable($tableGateway);
                        return $table;
                    },
                'NewsletterSettingsTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new NewsletterSettings());
                        return new TableGateway('cms_newsletter_settings', $dbAdapter, null, $resultSetPrototype);
                    },
            ),
        );
    }		
}