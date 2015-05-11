<?php
namespace CmsIr\Users\Controller;

use CmsIr\Authentication\Model\Authentication;
use CmsIr\Users\Form\ChangePasswordFilter;
use CmsIr\Users\Form\ChangePasswordForm;
use CmsIr\Users\Model\Users;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;
use CmsIr\Users\Form\UserForm;
use CmsIr\Users\Form\UserFormFilter;
use CmsIr\Users\Form\UsereditFormFilter;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;

use Zend\Mail\Message;

class UsersController extends AbstractActionController
{
    protected $usersTable;
    protected $authUsersTable;
    protected $uploadDir = 'public/files/users/';
    protected $appName = 'Cms-ir';

    public function usersListAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array( 'name', 'surname', 'email');

            $listData = $this->getUsersTable()->getDatatables($columns,$data);
            $output = array(
                "sEcho" => $this->getRequest()->getPost('sEcho'),
                "iTotalRecords" => $listData['iTotalRecords'],
                "iTotalDisplayRecords" => $listData['iTotalDisplayRecords'],
                "aaData" => $listData['aaData']
            );

            $jsonObject = Json::encode($output, true);
            echo $jsonObject;
            return $this->response;
        }
		return new ViewModel();
	}

    public function previewAction()
    {
        $id = $this->params()->fromRoute('id');

        $user = $this->getUsersTable()->getUser($id);

        if(!$user) {
            return $this->redirect()->toRoute('users');
        }

        $form = new UserForm();

        $config = $this->getServiceLocator()->get('Config');
        $aclRoles = $config['acl']['roles'];

        $tmpArrayRoles = array();
        $i = 1;

        foreach ($aclRoles as $keyRole => $role) {
            if($user->getRole() == $i) {
                $tmp = array(
                    'value' => $i,
                    'label' => ucfirst($keyRole),
                    'selected' => true
                );
            } else {
                $tmp = array(
                    'value' => $i,
                    'label' => ucfirst($keyRole),
                );
            }
            array_push($tmpArrayRoles, $tmp);
            $i++;
        }
        $form->get('role')->setValueOptions($tmpArrayRoles);
        $form->bind($user);

        $viewParams = array();
        $viewParams['form'] = $form;
        return new ViewModel($viewParams);
    }

    public function createAction()
    {
        $form = new UserForm();

        $config = $this->getServiceLocator()->get('Config');
        $aclRoles = $config['acl']['roles'];

        $tmpArrayRoles = array();
        $i = 1;
        foreach ($aclRoles as $keyRole => $role) {
            $tmp = array(
                'value' => $i,
                'label' => ucfirst($keyRole)
            );
            array_push($tmpArrayRoles, $tmp);
            $i++;
        }
        $form->get('role')->setValueOptions($tmpArrayRoles);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new UserFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                $data = $this->prepareData($data);

                $user = new Users();
                $user->exchangeArray($data[0]);
                $this->getUsersTable()->saveUser($user);
                $this->sendConfirmationEmail($user, $data[1]);
                $this->flashMessenger()->addMessage('Użytkownik został dodany poprawnie.');

                return $this->redirect()->toRoute('users');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        return new ViewModel($viewParams);
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');

        $user = $this->getUsersTable()->getUser($id);
        //var_dump($user);die;
        if(!$user) {
            return $this->redirect()->toRoute('users');
        }

        $form = new UserForm();

        $config = $this->getServiceLocator()->get('Config');
        $aclRoles = $config['acl']['roles'];

        $tmpArrayRoles = array();
        $i = 1;

        foreach ($aclRoles as $keyRole => $role) {
            if($user->getRole() == $i) {
                $tmp = array(
                    'value' => $i,
                    'label' => ucfirst($keyRole),
                    'selected' => true
                );
            } else {
                $tmp = array(
                    'value' => $i,
                    'label' => ucfirst($keyRole),
                );
            }
            array_push($tmpArrayRoles, $tmp);
            $i++;
        }
        $form->get('role')->setValueOptions($tmpArrayRoles);
        $form->bind($user);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new UsereditFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getUsersTable()->saveUser($user);

                $this->flashMessenger()->addMessage('Użytkownik został zedytowany poprawnie.');
                return $this->redirect()->toRoute('users');
            }
        }
        $viewParams = array();
        $viewParams['form'] = $form;
        return new ViewModel($viewParams);
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('users');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');
                $this->getUsersTable()->deleteUser($id);
                $this->flashMessenger()->addMessage('Użytkownik został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('users');
        }

        return array(
            'id'    => $id,
            'user' => $this->getUsersTable()->getUser($id)
        );
    }

    public function uploadAction ()
    {
        if (!empty($_FILES)) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];

            $file = explode('.', $targetFile);
            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->uploadDir.$targetFile)) {
                echo $targetFile;
            } else {
                echo 0;
            }

        }
        return $this->response;
    }

    public function generatePassword($l = 8, $c = 0, $n = 0, $s = 0)
    {
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

    public function prepareData($data)
    {
        $randomPassword = uniqid();
        $data['active'] = 1;
        $data['password_salt'] = $this->generateDynamicSalt();
        $data['password'] = $this->encriptPassword(
            $this->getStaticSalt(),
            $randomPassword,
            $data['password_salt']
        );
        $data['registration_date'] = date('Y-m-d H:i:s');
        $date = new \DateTime();
        $data['registration_date'] = $date->format('Y-m-d H:i:s');
        $data['registration_token'] = md5(uniqid(mt_rand(), true));
        $data['email_confirmed'] = 1;
        return array($data, $randomPassword);
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

    public function generateDynamicSalt()
    {
        $dynamicSalt = '';
        for ($i = 0; $i < 50; $i++) {
            $dynamicSalt .= chr(rand(35, 126));
        }
        return $dynamicSalt;
    }

    public function sendConfirmationEmail(Users $user, $password)
    {
        $transport = $this->getServiceLocator()->get('mail.transport');

        $content = "Utworzono konto dla ciebie w serwisie " . $this->appName . "<br> Twoje hasło to: " .  $password;

        $html = new MimePart($content);
        $html->type = "text/html";
        $body = new MimeMessage();
        $body->setParts(array($html,));

        $message = new Message();
        $this->getRequest()->getServer();
        $message->addTo($user->getEmail())
            ->addFrom('website@dnastudio.pl')
            ->setSubject('Rejestracja w serwisie: ' . $this->appName)
            ->setBody($body);
        $message->setEncoding('utf-8');
        $transport->send($message);
    }

    public function changePasswordAction()
    {
        $form = new ChangePasswordForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new ChangePasswordFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $request->getPost()->toArray();

                $sm = $this->getServiceLocator();
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                $config = $this->getServiceLocator()->get('Config');
                $staticSalt = $config['static_salt'];

                $user = $this->identity();

                $authAdapter = new AuthAdapter($dbAdapter, 'cms_users', 'email', 'password', "MD5(CONCAT('$staticSalt', ?, password_salt)) AND active = 1" );
                $authAdapter
                    ->setIdentity($user->email)
                    ->setCredential($data['password_last']);

                $result = $authAdapter->authenticate();

                if ($result->getCode() == Result::FAILURE_CREDENTIAL_INVALID) {
                    $message = 'Błędne hasło';

                    $viewParams = array();
                    $viewParams['form'] = $form;
                    $viewParams['message'] = $message;
                    return new ViewModel($viewParams);
                } else {

                    $data = $this->prepareUser($data, $user);

                    $authUser = new Authentication();
                    $authUser->exchangeArray($data);
                    $this->getAuthUsersTable()->saveUser($authUser);

                    $this->flashMessenger()->addMessage('Poprawnie zmieniono hasło.');
                    return $this->redirect()->toRoute('users');
                }
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        return new ViewModel($viewParams);
    }

    public function prepareUser($data, $user)
    {
        $data['password_salt'] = $this->generateDynamicSalt();
        $data['password'] = $this->encriptPassword(
            $this->getStaticSalt(),
            $data['password_new'],
            $data['password_salt']
        );
        $data['id'] = $user->id;
        $data['name'] = $user->name;
        $data['surname'] = $user->surname;
        $data['email'] = $user->email;
        $data['email_confirmed'] = 1;
        $data['role'] = $user->role;
        $data['filename'] = $user->filename;
        $data['registration_date'] = $user->registration_date;
        $data['registration_token'] = $user->registration_token;
        $data['position'] = $user->position;
        $data['facebook'] = $user->facebook;
        $data['twitter'] = $user->twitter;
        $data['google'] = $user->google;
        $data['active'] = 1;

        return $data;
    }

    /**
     * @return \CmsIr\Users\Model\UsersTable
     */
    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('CmsIr\Users\Model\UsersTable');
        }
        return $this->usersTable;
    }

    /**
     * @return \CmsIr\Authentication\Model\UsersTable
     */
    public function getAuthUsersTable()
    {
        if (!$this->authUsersTable) {
            $sm = $this->getServiceLocator();
            $this->authUsersTable = $sm->get('CmsIr\Authentication\Model\UsersTable');
        }
        return $this->authUsersTable;
    }
}