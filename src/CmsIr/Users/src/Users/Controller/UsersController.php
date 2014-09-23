<?php
namespace CmsIr\Users\Controller;

use CmsIr\Users\Model\Users;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;
use CmsIr\Users\Form\UserForm;
use CmsIr\Users\Form\UserFormFilter;

use Zend\Mail\Message;

class UsersController extends AbstractActionController
{
    protected $usersTable;
    protected $uploadDir = 'public/files/users/';
    protected $appName = 'Cms-ir';

    public function usersListAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array( 'name', 'surname', 'email');

            $listData = $this->getUsersTable()->findBy($columns,$data);
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

        return new ViewModel();
    }

    public function createAction()
    {
        $form = new UserForm();

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
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        return new ViewModel($viewParams);
    }

    public function editAction()
    {
        return new ViewModel();
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('users-list');
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

            return $this->redirect()->toRoute('users-list');
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
        $data['active'] = 0;
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
        $data['email_confirmed'] = 0;
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
        $message = new Message();
        $this->getRequest()->getServer();
        $message->addTo($user->getEmail())
            ->addFrom('mailer@web-ir.pl')
            ->setSubject('Rejestracja w serwisie: ' . $this->appName)
            ->setBody("Utworzono konto dla ciebie w serwisie " . $this->appName . "<br> Twoje hasło to: " .  $password);
        $transport->send($message);
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
}