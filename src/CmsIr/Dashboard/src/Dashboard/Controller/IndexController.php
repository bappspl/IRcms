<?php
namespace CmsIr\Dashboard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

use Zend\Db\Adapter\Adapter as DbAdapter;

use Zend\Authentication\Adapter\DbTable as AuthAdapter;

use CmsIr\Authentication\Model\Authentication;
use CmsIr\Authentication\Form\AuthenticationForm;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $loggedUser = $auth->getIdentity();
            $this->layout()->loggedUser = $loggedUser;
        }

        $service = $this->getServiceLocator()->get('CmsIr\Zf2SlugGenerator\SlugService');
        $slug = $service->create('My String To Slug', false);

        $viewParams = array();
        $viewParams['slug'] = $slug;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }
}