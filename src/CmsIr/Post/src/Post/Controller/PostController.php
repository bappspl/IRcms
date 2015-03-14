<?php
namespace CmsIr\Post\Controller;

use CmsIr\Post\Form\PostForm;
use CmsIr\Post\Form\PostFormFilter;
use CmsIr\Post\Model\Post;
use CmsIr\Post\Model\PostFile;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;

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

        $currentWebsiteId = $_COOKIE['website_id'];

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('name', 'date');

            $listData = $this->getPostTable()->getPostDatatables($columns, $data, $category, $currentWebsiteId, $userId);
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
        $currentWebsiteId = $_COOKIE['website_id'];

        $userRoleId = $this->identity()->role;
        if($userRoleId < 3) $form->get('status_id')->setAttribute('disabled', 'disabled');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new PostFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $post = new Post();
                $post->exchangeArray($form->getData());
                $post->setCategory($category);
                $post->setDate(date('Y-m-d'));
                $post->setAuthorId($this->identity()->id);
                $post->setWebsiteId($currentWebsiteId);

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

                $this->flashMessenger()->addMessage('Wpis został utworzony poprawnie.');
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
        $currentWebsiteId = $_COOKIE['website_id'];

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
        $form->bind($post);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new PostFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $post->setCategory($category);
                $post->setDate(date('Y-m-d'));
                $post->setAuthorId($this->identity()->id);
                $post->setWebsiteId($currentWebsiteId);

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
            return $this->redirect()->toRoute('post-list', array('category' => $category));
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

            return $this->redirect()->toRoute('post-list', array('category' => $category));
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

}