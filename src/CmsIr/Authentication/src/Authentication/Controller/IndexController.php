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
	protected $usersTable;	
	
    public function indexAction()
    {
		return new ViewModel();
	}	
	
    public function loginAction()
	{
		$user = $this->identity();
		$form = new AuthenticationForm();
		$messages = null;

		$request = $this->getRequest();
        if ($request->isPost()) {

			$authFormFilters = new Authentication();
            $fromData = $request->getPost()->toArray();
            $data = array_merge(
                $fromData
            );
			$form->setData($data);

			 if ($form->isValid()) {

//				$data = $form->getData();

                $sm = $this->getServiceLocator();

                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
				$config = $sm->get('Config');

//				$staticSalt = $config['static_salt'];

//				$authAdapter = new AuthAdapter($dbAdapter, 'cms_users', 'email', 'password', "MD5(CONCAT('$staticSalt', ?, password_salt)) AND active = 1" );
				$authAdapter = new AuthAdapter($dbAdapter, 'cms_users', 'email', 'password', "active = 1" );

				$authAdapter
					->setIdentity($data['email'])
					->setCredential($data['password']);
				
				$auth = new AuthenticationService();
				$result = $auth->authenticate($authAdapter);

				switch ($result->getCode()) {
					case Result::FAILURE_IDENTITY_NOT_FOUND:
						// do stuff for nonexistent identity
					break;

					case Result::FAILURE_CREDENTIAL_INVALID:
						// do stuff for invalid credential
					break;

					case Result::SUCCESS:

						$storage = $auth->getStorage();
						$storage->write($authAdapter->getResultRowObject(null, 'password'));

						$time = 1209600;

//                        if ($data['rememberme']) {
//							$sessionManager = new \Zend\Session\SessionManager();
//							$sessionManager->rememberMe($time);
//						}
                        return $this->redirect()->toRoute('home');
					break;

					default:

                    break;
				}				
				foreach ($result->getMessages() as $message) {
					$messages .= "$message\n"; 
				}			
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
        $this->getUsersTable()->autoLogout($identity->id);
		$auth->clearIdentity();

		$sessionManager = new \Zend\Session\SessionManager();
		$sessionManager->forgetMe();

		return $this->redirect()->toRoute('home');	
	}	

	public function checkloginajaxAction()
	{
		$request = $this->getRequest();
			 if ($request->isPost()) {
				$data = $request->getPost();
				$sm = $this->getServiceLocator();
				$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
				
				$config = $this->getServiceLocator()->get('Config');
				$staticSalt = $config['static_salt'];

				$authAdapter = new AuthAdapter($dbAdapter, 'pms_user', 'email', 'password', "MD5(CONCAT('$staticSalt', ?, password_salt)) AND active = 1" );
				$authAdapter
					->setIdentity($data['login'])
					->setCredential($data['password']);
				
				$auth = new AuthenticationService();
				$result = $auth->authenticate($authAdapter);			
				
				switch ($result->getCode()) {
					case Result::FAILURE_IDENTITY_NOT_FOUND:
						// do stuff for nonexistent identity
						$tablica['wynik'] = 'failed';
						break;

					case Result::FAILURE_CREDENTIAL_INVALID:
						$tablica['wynik'] = 'failed';
						// do stuff for invalid credential
						break;

					case Result::SUCCESS:
						$storage = $auth->getStorage();
						$storage->write($authAdapter->getResultRowObject(
							null,
							'password'
						));
						$time = 1209600; // 14 days 1209600/3600 = 336 hours => 336/24 = 14 days
						if ($data['rememberme']) {
							$sessionManager = new \Zend\Session\SessionManager();
							$sessionManager->rememberMe($time);
						}
                        $identity = $auth->getIdentity();
                        $this->getUsersTable()->autoLogin($identity->id);
						$tablica['wynik'] = 'succes';
						break;

					default:
						$tablica['wynik'] = 'failed';
						// do stuff for other failure
						break;
				}
				echo json_encode($tablica);

			}
			return $this->response;
	}

	public function checkIfLoginExistsAjaxAction()
	{
		$request = $this->getRequest();
    	if ($request->isPost()){ 
    		$login = $this->getRequest()->getPost('login');	
    		$login = trim($login);	
    		$getLogin = $this->getUsersTable()->findLogin($login);
    		if ($getLogin) {
    			$tablica['wynik'] = 'succes';
           		echo json_encode($tablica);
    		} else {
    			$tablica['wynik'] = 'failed';
           		echo json_encode($tablica);
    		}    		
    	}
    	return $this->response;
	}	

	public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('CmsIR\Authentication\Model\UsersTable');
        }
        return $this->usersTable;
    }
	
}