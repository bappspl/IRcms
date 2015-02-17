<?php
namespace CmsIr\Post\Controller;

use CmsIr\Newsletter\Model\Subscriber;
use CmsIr\Post\Form\PostForm;
use CmsIr\Post\Form\PostFormFilter;
use CmsIr\Post\Model\Post;
use CmsIr\Post\Model\PostFile;
use CmsIr\System\Model\Status;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class PostController extends AbstractActionController
{
    protected $uploadDir = 'public/temp_files/post/';
    protected $destinationUploadDir = 'public/files/post/';
    protected $appName = 'Cms-ir';

    public function postListAction()
    {
        $category = $this->params()->fromRoute('category');

        $userRoleId = $this->identity()->role;
        $userRoleId < 3 ? $userId = $this->identity()->id : $userId = null;

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('name', 'url', 'date');

            $listData = $this->getPostTable()->getPostDatatables($columns, $data, $category, $userId);
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

        $viewParams = array();
        $viewParams['category'] = $category;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
		return  $viewModel;
	}

    public function createPostAction ()
    {
        $form = new PostForm();
        $category = $this->params()->fromRoute('category');

        $userRoleId = $this->identity()->role;
        if($userRoleId < 3) $form->get('status_id')->setAttribute('disabled', 'disabled');

        $users = $this->getUsersTable()->getAll();
        $tmpUsersArray = array();
        foreach($users as $user)
        {
            $tmp = array(
                'value' => $user->getId(),
                'label' => $user->getName() . ' ' . $user->getSurname(),
            );
            array_push($tmpUsersArray, $tmp);
        }

        $form->get('author_id')->setValueOptions($tmpUsersArray);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new PostFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $post = new Post();
                $post->exchangeArray($form->getData());
                $post->setCategory($category);

                if($userRoleId < 3) $post->setStatusId(2);
                $id = $this->getPostTable()->save($post);

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $file)
                    {
                        $fileSize = filesize($this->uploadDir.'/'.$file);

                        $postFile = new PostFile();
                        $postFile->setFilename($file);
                        $postFile->setPostId($id);
                        $postFile->setSize($fileSize);

                        $this->getPostFileTable()->save($postFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                // Only for DNA
                if($post->getStatusId() == 1)
                {
                    $newsletterContent = "Na stronie pojawił się nowy artykuł! <br>" .
                                         "Kliknij w poniższy link, aby go przeczytać: <a href='" .
                                         $this->getRequest()->getServer('HTTP_ORIGIN') .
                                         $this->url()->fromRoute('oneNews', array('slug' => $post->getUrl())) . "'>" . $post->getName() . "</a>";

                    /** @var $confirmedStatus Status */
                    $confirmedStatus = $this->getStatusTable()->getOneBy(array('slug' => 'confirmed'));
                    $confirmedStatusId = $confirmedStatus->getId();

                    $subscribers = $this->getSubscriberTable()->getBy(array('status_id' => $confirmedStatusId));

                    $subscriberEmails = array();
                    /** @var $subscriber Subscriber */
                    foreach($subscribers as $subscriber)
                    {
                        $subscriberEmails[$subscriber->getEmail()] = $subscriber->getEmail();
                    }
                    $this->sendEmails($subscriberEmails, "Nowy artykuł na stronie DNA!", $newsletterContent);

                    $this->flashMessenger()->addMessage('Wpis został utworzony poprawnie oraz wysłano newsletter.');

                } else
                {
                    $this->flashMessenger()->addMessage('Wpis został utworzony poprawnie.');

                }

                return $this->redirect()->toRoute('post', array('category' => $category));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['category'] = $category;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editPostAction ()
    {
        $id = $this->params()->fromRoute('post_id');
        $category = $this->params()->fromRoute('category');

        $userRoleId = $this->identity()->role;

        /**
         * @var $post Post
         */
        $post = $this->getPostTable()->getOneBy(array('id' => $id));
        $postFiles = $this->getPostFileTable()->getBy(array('post_id' => $id));

        if(!$post) {
            return $this->redirect()->toRoute('post', array('category' => $category));
        }

        $form = new PostForm();
        if($userRoleId < 3) $form->get('status_id')->setAttribute('disabled', 'disabled');

        $users = $this->getUsersTable()->getAll();
        $tmpUsersArray = array();

        $postAuthorId = $post->getAuthorId();
        foreach($users as $user)
        {
            if($postAuthorId == $user->getId())
            {
                $tmp = array(
                    'value' => $user->getId(),
                    'label' => $user->getName() . ' ' . $user->getSurname(),
                    'selected' => true
                );
            } else
            {
                $tmp = array(
                    'value' => $user->getId(),
                    'label' => $user->getName() . ' ' . $user->getSurname(),
                );
            }

            array_push($tmpUsersArray, $tmp);
        }

        $form->bind($post);
        $form->get('author_id')->setValueOptions($tmpUsersArray);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new PostFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $post->setCategory($category);
                if($userRoleId < 3) $post->setStatusId(2);

                $id = $this->getPostTable()->save($post);

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $file)
                    {
                        $fileSize = filesize($this->uploadDir.'/'.$file);

                        $postFile = new PostFile();
                        $postFile->setFilename($file);
                        $postFile->setPostId($id);
                        $postFile->setSize($fileSize);

                        $this->getPostFileTable()->save($postFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $this->flashMessenger()->addMessage('Wpis został zedytowany poprawnie.');

                return $this->redirect()->toRoute('post', array('category' => $category));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['postFiles'] = $postFiles;
        $viewParams['category'] = $category;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function previewPostAction ()
    {
        $id = $this->params()->fromRoute('post_id');
        $category = $this->params()->fromRoute('category');

        /**
         * @var $post Post
         */
        $post = $this->getPostTable()->getOneBy(array('id' => $id));
        $postFiles = $this->getPostFileTable()->getBy(array('post_id' => $id));

        if(!$post) {
            return $this->redirect()->toRoute('post', array('category' => $category));
        }

        $form = new PostForm();
        $form->bind($post);

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['postFiles'] = $postFiles;
        $viewParams['category'] = $category;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deletePostAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('post_id');
        $category = (int) $this->params()->fromRoute('category');

        if (!$id) {
            return $this->redirect()->toRoute('post', array('category' => $category));
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');

                $postFiles = $this->getPostFileTable()->getBy(array('post_id' => $id));

                if((!empty($postFiles)))
                {
                    foreach($postFiles as $file)
                    {
                        unlink('./public/files/post/'.$file->getFilename());
                        $this->getPostFileTable()->deletePostFile($file->getId());
                    }
                }

                $this->getPostTable()->deletePost($id);


                $this->flashMessenger()->addMessage('Post został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('post', array('category' => $category));
        }

        return array();
    }

    public function uploadFilesAction ()
    {
        if (!empty($_FILES)) {
            //var_dump($_FILES);die;
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

    public function deletePhotoAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            $filePath = $request->getPost('filePath');

            if($id != 0)
            {
                $this->getPostFileTable()->deletePostFile($id);
                unlink('./public'.$filePath);
            } else
            {
                unlink('./public'.$filePath);
            }
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
        return $this->response;
    }

    public function sendEmails($emails, $subject, $content)
    {
        $transport = $this->getServiceLocator()->get('mail.transport');

        $html = new MimePart($content);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($html));

        foreach($emails as $email)
        {
            $message = new Message();
            $this->getRequest()->getServer();
            $message->addTo($email)
                ->addFrom('mailer@web-ir.pl')
                ->setEncoding('UTF-8')
                ->setSubject($subject)
                ->setBody($body);
            $transport->send($message);
        }
    }

    /**
     * @return \CmsIr\Post\Model\PostTable
     */
    public function getPostTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Model\PostTable');
    }

    /**
     * @return \CmsIr\Post\Model\PostFileTable
     */
    public function getPostFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Model\PostFileTable');
    }

    /**
     * @return \CmsIr\Users\Model\UsersTable
     */
    public function getUsersTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Users\Model\UsersTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberTable
     */
    public function getSubscriberTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberTable');
    }
}