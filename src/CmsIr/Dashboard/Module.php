<?php
namespace CmsIr\Dashboard;

// Add this for Table Date Gateway
use CmsIr\System\Model\LogEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $application         = $e->getApplication();
        $eventManager        = $application->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sm = $application->getServiceManager();
        $events = $this->getLogEventTable($sm)->getBy(array(), array('date' => 'desc'), 10);

        /* @var $event LogEvent */
//        foreach($events as $event)
//        {
//            $user = $event->getUser();
//            $username = explode('@', $user);
//            $name = $username[0];
//
//            $cmsUser = $this->getUsersTable($sm)->findByName($name);
//
//            $event->setUser($cmsUser->getName() . ' ' . $cmsUser->getSurname());
//            $event->setFilename($cmsUser->getFilename());
//        }

        $unreadEvents = $this->getLogEventTable($sm)->getByAndCount(array('viewed' => 0));

        $viewModel = $e->getViewModel();

        $viewModel->latestEvents = $events;
        $viewModel->unreadEvents = $unreadEvents;

        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $loggedUser = $auth->getIdentity();
            $viewModel->loggedUser = $loggedUser;
        }
    }

    public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager();
//        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, array($this, 'onMergeConfig'));
    }

    public function onMergeConfig(ModuleEvent $e)
    {
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $configListner = $e->getConfigListener();
            $config = $configListner->getMergedConfig(false);

            $loggedUser = $auth->getIdentity();
            $email = $loggedUser->email;
            $username = explode('@', $email);
            $password = $loggedUser->registration_token;

            $config['db']['username'] = $username[0];
            $config['db']['password'] = $password;

            $configListner->setMergedConfig($config);
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

    /**
     * @return \CmsIr\System\Model\LogEventTable
     */
    public function getLogEventTable($sm)
    {
        return $sm->get('CmsIr\System\Model\LogEventTable');
    }

    /**
     * @return \CmsIr\Users\Model\UsersTable
     */
    public function getUsersTable($sm)
    {
        return $sm->get('CmsIr\Users\Model\UsersTable');
    }
}