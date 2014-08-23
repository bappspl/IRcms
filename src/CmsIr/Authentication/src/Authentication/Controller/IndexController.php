<?php
namespace CmsIr\Authentication\Controller;

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
		return new ViewModel();
	}	
	
    public function loginAction()
	{
        $this->layout('layout/authentication');
		$user = $this->identity();
		$form = new AuthenticationForm();
		$messages = null;

		$request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            if(!empty($data['email']) && !empty($data['password'])) {
                $sm = $this->getServiceLocator();
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                $config = $this->getServiceLocator()->get('Config');
                $staticSalt = $config['static_salt'];

                $authAdapter = new AuthAdapter($dbAdapter, 'cms_users', 'email', 'password', "MD5(CONCAT('$staticSalt', ?, password_salt)) AND active = 1" );
                $authAdapter
                    ->setIdentity($data['email'])
                    ->setCredential($data['password']);

                $auth = new AuthenticationService();
                $result = $auth->authenticate($authAdapter);

                switch ($result->getCode()) {
                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        $messages = 'Nie istnieje taki użytkownik';
                    break;

                    case Result::FAILURE_CREDENTIAL_INVALID:
                        $messages = 'Błędny login lub hasło';
                    break;

                    case Result::SUCCESS:
                        $storage = $auth->getStorage();
                        $storage->write($authAdapter->getResultRowObject(
                            null,
                            'password'
                        ));
                        $time = 1209600; // 14 days
                        if ($data['rememberme']) {
                            $sessionManager = new \Zend\Session\SessionManager();
                            $sessionManager->rememberMe($time);
                        }
                        return $this->redirect()->toRoute('dashboard');
                    break;

                    default:
                    break;
                }
            } else {
                $messages = 'Uzupełnij wszystkie pola';
            }
        }
		return new ViewModel(array('form' => $form, 'messages' => $messages));
	}

	public function logoutAction()
	{
		$auth = new AuthenticationService();

		if ($auth->hasIdentity()) {
			$identity = $auth->getIdentity();
		}
		$auth->clearIdentity();

		$sessionManager = new \Zend\Session\SessionManager();
		$sessionManager->forgetMe();

		return $this->redirect()->toRoute('home');	
	}
}