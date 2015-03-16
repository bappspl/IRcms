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
        if(!isset($_COOKIE['website_id']) || $_COOKIE['website_id'] == null)
        {
            $_COOKIE['website_id'] = 1;
        }
        $viewParams = array();
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }
}