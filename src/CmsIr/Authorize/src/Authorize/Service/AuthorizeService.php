<?php

namespace CmsIr\Authorize\Service;

use CmsIr\Authorize\Acl\Acl;
use Zend\EventManager\EventInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class AuthorizeService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function onRoute(\Zend\EventManager\EventInterface $e) // Event manager of the app
    {
        $application = $e->getApplication();
        $routeMatch = $e->getRouteMatch();
        $sm = $application->getServiceManager();
        $auth = $sm->get('Zend\Authentication\AuthenticationService');
        $config = $sm->get('Config');
        $acl = new Acl($config);
        // everyone is guest untill it gets logged in
        $role = Acl::DEFAULT_ROLE; // The default role is guest $acl
        // with Doctrine
        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            $usrlId = $user->role; // Use a view to get the name of the role
            // TODO we don't need that if the names of the roles are comming from the DB
            switch ($usrlId) {
                case 1 :
                    $role = Acl::DEFAULT_ROLE; // guest
                    break;
                case 2 :
                    $role = 'user';
                    break;
                case 3 :
                    $role = 'admin';
                    break;
                case 4 :
                    $role = 'superadmin';
                    break;
                default :
                    $role = Acl::DEFAULT_ROLE; // guest
                    break;
            }
        }

        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');

        if (!$acl->hasResource($controller)) {
            throw new \Exception('Resource ' . $controller . ' not defined');
        }

        if (!$acl->isAllowed($role, $controller, $action)) {
            $url = $e->getRouter()->assemble(array(), array('name' => 'login'));
            $response = $e->getResponse();

            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit;
        }
    }

    /**
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}
