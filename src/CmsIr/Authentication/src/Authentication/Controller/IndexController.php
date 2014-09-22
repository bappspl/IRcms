<?php
namespace CmsIr\Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

use Zend\Db\Adapter\Adapter as DbAdapter;

use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;

use CmsIr\Authentication\Model\Authentication;
use CmsIr\Authentication\Form\AuthenticationForm;
use CmsIr\Authentication\Form\AuthenticationFormFilter;
use CmsIr\Authentication\Form\ForgottenPasswordForm;
use CmsIr\Authentication\Form\ForgottenPasswordFilter;
use CmsIr\Authentication\Form\RegistrationForm;
use CmsIr\Authentication\Form\RegistrationFilter;


use Zend\Mail\Message;

class IndexController extends AbstractActionController
{
    protected $usersTable;

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
            $form->setInputFilter(new AuthenticationFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

//            if(!empty($data['email']) && !empty($data['password'])) {
            if ($form->isValid()) {
                $data = $form->getData();
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

    public function forgottenPasswordAction()
    {
        $this->layout('layout/authentication');
        $form = new ForgottenPasswordForm();
        $messages = null;
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new ForgottenPasswordFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();

                $valid = new \Zend\Validator\Db\RecordExists(array('table' => 'cms_users', 'field' => 'email'));
                $valid->setAdapter($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));

                if ($valid->isValid($data['email'])){
                    $email = $data['email'];
                    $usersTable = $this->getUsersTable();
                    $auth = $usersTable->getUserByEmail($email);
                    $password = $this->generatePassword();
                    $auth->password = $this->encriptPassword($this->getStaticSalt(), $password, $auth->password_salt);
                    $usersTable->saveUser($auth);
                    $this->sendPasswordByEmail($email, $password);
                    $messages = 'Nowe hasło zostało wysłane na emaila';
                } else {
                    $messages = 'Błędny login lub hasło';
                }
            } else {
                $messages = 'Uzupełnij wszystkie pola';
            }
        }
        return new ViewModel(array('form' => $form, 'messages' => $messages));
    }

    public function registrationAction()
    {
        $this->layout('layout/authentication');
        $form = new RegistrationForm();
        $messages = null;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new RegistrationFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $data = $this->prepareData($data);
                $auth = new Authentication();
                $auth->exchangeArray($data);
                $this->getUsersTable()->saveUser($auth);
                $this->sendConfirmationEmail($auth);
                $messages = 'Wysłano maila z linkiem potwierdzającym';
            }
        }
        return new ViewModel(array('form' => $form, 'messages' => $messages));
    }

    public function confirmEmailAction()
    {
        $this->layout('layout/authentication');
        $token = $this->params()->fromRoute('id');
        $viewModel = new ViewModel(array('token' => $token));
        try {
            $user = $this->getUsersTable()->getUserByToken($token);
            $id = $user->id;
            $this->getUsersTable()->activateUser($id);
        }
        catch(\Exception $e) {
            $viewModel->setTemplate('cmsir/index/confirm-email-error.phtml');
        }
        return $viewModel;
    }

    public function sendConfirmationEmail($auth)
    {
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();
        $this->getRequest()->getServer();
        $message->addTo($auth->email)
            ->addFrom('mailer@web-ir.pl')
            ->setSubject('Prosimy o potwierdzenie rejestracji!')
            ->setBody("W celu potwierdzenia rejestracji kliknij w link => " .
                $this->getRequest()->getServer('HTTP_ORIGIN') .
                $this->url()->fromRoute('confirm-email', array('id' => $auth->registration_token)));
        $transport->send($message);
    }

    public function sendPasswordByEmail($usr_email, $password)
    {
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();
        $this->getRequest()->getServer();  //Server vars
        $message->addTo($usr_email)
            ->addFrom('mailer@web-ir.pl')
            ->setSubject('Twoje hasło zostało zmienione!')
            ->setBody("Twoje hasło na stronie  " .
                $this->getRequest()->getServer('HTTP_ORIGIN') .
                ' zostało zmienione. Twoje nowe hasło to: ' .
                $password
            );
        $message->setEncoding('UTF-8');
        $transport->send($message);
    }

    public function generatePassword($l = 8, $c = 0, $n = 0, $s = 0) {
        // get count of all required minimum special chars
        $count = $c + $n + $s;
        $out = '';
        // sanitize inputs; should be self-explanatory
        if(!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
            trigger_error('Argument(s) not an integer', E_USER_WARNING);
            return false;
        }
        elseif($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
            trigger_error('Argument(s) out of range', E_USER_WARNING);
            return false;
        }
        elseif($c > $l) {
            trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
            return false;
        }
        elseif($n > $l) {
            trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
            return false;
        }
        elseif($s > $l) {
            trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
            return false;
        }
        elseif($count > $l) {
            trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
            return false;
        }

        // all inputs clean, proceed to build password

        // change these strings if you want to include or exclude possible password characters
        $chars = "abcdefghijklmnopqrstuvwxyz";
        $caps = strtoupper($chars);
        $nums = "0123456789";
        $syms = "!@#$%^&*()-+?";

        // build the base password of all lower-case letters
        for($i = 0; $i < $l; $i++) {
            $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        // create arrays if special character(s) required
        if($count) {
            // split base password to array; create special chars array
            $tmp1 = str_split($out);
            $tmp2 = array();

            // add required special character(s) to second array
            for($i = 0; $i < $c; $i++) {
                array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
            }
            for($i = 0; $i < $n; $i++) {
                array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
            }
            for($i = 0; $i < $s; $i++) {
                array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
            }

            // hack off a chunk of the base password array that's as big as the special chars array
            $tmp1 = array_slice($tmp1, 0, $l - $count);
            // merge special character(s) array with base password array
            $tmp1 = array_merge($tmp1, $tmp2);
            // mix the characters up
            shuffle($tmp1);
            // convert to string for output
            $out = implode('', $tmp1);
        }

        return $out;
    }

    public function encriptPassword($staticSalt, $password, $dynamicSalt)
    {
        return $password = md5($staticSalt . $password . $dynamicSalt);
    }

    public function getStaticSalt()
    {
        $staticSalt = '';
        $config = $this->getServiceLocator()->get('Config');
        $staticSalt = $config['static_salt'];
        return $staticSalt;
    }

    public function prepareData($data)
    {
        $data['active'] = 0;
        $data['password_salt'] = $this->generateDynamicSalt();
        $data['password'] = $this->encriptPassword(
            $this->getStaticSalt(),
            $data['password'],
            $data['password_salt']
        );
        $data['registration_date'] = date('Y-m-d H:i:s');
        $date = new \DateTime();
        $data['registration_date'] = $date->format('Y-m-d H:i:s');
        $data['registration_token'] = md5(uniqid(mt_rand(), true));
        $data['email_confirmed'] = 0;
        return $data;
    }

    public function generateDynamicSalt()
    {
        $dynamicSalt = '';
        for ($i = 0; $i < 50; $i++) {
            $dynamicSalt .= chr(rand(35, 126));
        }
        return $dynamicSalt;
    }

    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('CmsIr\Authentication\Model\UsersTable');
        }
        return $this->usersTable;
    }
}