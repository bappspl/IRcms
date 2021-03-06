<?php
namespace CmsIr\Post\Controller;

use CmsIr\File\Model\File;
use CmsIr\Newsletter\Model\Subscriber;
use CmsIr\Post\Form\PostForm;
use CmsIr\Post\Form\PostFormFilter;
use CmsIr\Post\Model\Post;
use CmsIr\System\Model\Status;
use CmsIr\Users\Model\Users;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Sql\Expression;
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
        $userRoleId < 1 ? $userId = $this->identity()->id : $userId = null;

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'date', 'statusId', 'status', 'id');

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
        $tags = $this->getTagService()->findAsAssocArray();

        $form = new PostForm($tags);
        $category = $this->params()->fromRoute('category');

        $userRoleId = $this->identity()->role;
        if($userRoleId < 1) $form->get('status_id')->setAttribute('disabled', 'disabled');

        /* @var $user Users */
        $users = $this->getUsersTable()->getBy(array(new Predicate\Operator('role', Predicate\Operator::OP_EQ, 2)));
        $arrUsers = array();

        foreach($users as $user) {
            $arrUsers[$user->getId()] = $user->getName() . ' ' . $user->getSurname();
        }

        $form->get('author_id')->setValueOptions($arrUsers);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new PostFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
//                $extraResult = $this->checkExtraFields($request->getPost(), $form->getData());

                $post = new Post();
                $post->exchangeArray($form->getData());
                $post->setCategory($category);

//                if(!empty($extraResult)) {
//                    $serializedExtraResult = serialize($extraResult);
//                    $post->setExtra($serializedExtraResult);
//                }

                if(is_null($post->getDate())) {
                    $post->setDate(new Expression('NOW()'));
                }

                if($userRoleId < 1) $post->setStatusId(2);
                $id = $this->getPostTable()->save($post);
                $this->getMetaService()->saveMeta('Post', $id, $request->getPost());

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));

                if(!empty($scannedDirectory)) {
                    foreach($scannedDirectory as $file) {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDir.'/'.$file);

                        $postFile = new File();
                        $postFile->setFilename($file);
                        $postFile->setEntityId($id);
                        $postFile->setEntityType('post');
                        $postFile->setMimeType($mimeType);

                        $this->getFileTable()->save($postFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                // Only for DNA
                if($post->getStatusId() == 1 && $category == 'news') {
//                    $newsletterContent = "Na stronie pojawił się nowy artykuł! <br>" .
//                        "Kliknij w poniższy link, aby go przeczytać: <a href='" .
//                        $this->getRequest()->getServer('HTTP_ORIGIN') .
//                        $this->url()->fromRoute('view-news', array('url' => $post->getUrl())) . "'>" . $post->getName() . "</a>";
//
//
//                    /** @var $confirmedStatus Status */
//                    $confirmedStatus = $this->getStatusTable()->getOneBy(array('slug' => 'confirmed'));
//                    $confirmedStatusId = $confirmedStatus->getId();
//
//                    $subscribers = $this->getSubscriberTable()->getBy(array('status_id' => $confirmedStatusId));
//
//                    $subscriberEmails = array();
//                    /** @var $subscriber Subscriber */
//                    foreach($subscribers as $subscriber)
//                    {
//                        $subscriberEmails[$subscriber->getEmail()] = $subscriber->getEmail();
//                    }
//
//                    $this->sendEmails($subscriberEmails, "Nowy artykul na stronie Apteki Grodzkiej!", $newsletterContent);

                    $this->getBlockService()->saveBlocks($id, 'Post', $request->getPost()->toArray(), 'title');

                    if(!empty($request->getPost()->toArray()['tag_id'])) {
                        $this->getTagService()->saveTags($request->getPost()->toArray()['tag_id'], $id, 'Post');
                    }

                    $this->flashMessenger()->addMessage('Wpis został utworzony poprawnie oraz wysłano newsletter.');

                } else {
                    $this->getBlockService()->saveBlocks($id, 'Post', $request->getPost()->toArray(), 'title');

                    if(!empty($request->getPost()->toArray()['tag_id'])) {
                        $this->getTagService()->saveTags($request->getPost()->toArray()['tag_id'], $id, 'Post');
                    }

                    $this->flashMessenger()->addMessage('Wpis został utworzony poprawnie.');
                }

                return $this->redirect()->toRoute('post', array('category' => $category));
            }
        } else {
            $this->emptyTempDirectory();
        }

        $config = $this->getServiceLocator()->get('config');
//        $extraFields = $config['extra_fields'];

        $fields = array();
//
//        if(array_key_exists($category, $extraFields['post'])) {
//            $fields = $extraFields['post'][$category];
//        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['extraFields'] = $fields;
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
        $postFiles = $this->getFileTable()->getBy(array('entity_id' => $id, 'entity_type' => 'Post'));
        $blocks = $this->getBlockService()->getBlocks($post, 'Post');

        if(!$post) {
            return $this->redirect()->toRoute('post', array('category' => $category));
        }

        /* @var $user Users */
        $users = $this->getUsersTable()->getBy(array(new Predicate\Operator('role', Predicate\Operator::OP_EQ, 2)));
        $arrUsers = array();

        foreach($users as $user) {
            $arrUsers[$user->getId()] = $user->getName() . ' ' . $user->getSurname();
        }

        $tags = $this->getTagService()->findAsAssocArray();

        $form = new PostForm($tags);
        $form->get('author_id')->setValueOptions($arrUsers);

        if($userRoleId < 1) $form->get('status_id')->setAttribute('disabled', 'disabled');

        $form->bind($post);

        $tagsForForm = $this->getTagService()->findAsAssocArrayForEntity( $id, 'Post');
        $form->get('tag_id')->setValue($tagsForForm);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new PostFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
//                $extraResult = $this->checkExtraFields($request->getPost(), $form->getData());
                $post->setCategory($category);

                if($userRoleId < 1) $post->setStatusId(2);
//                if(!empty($extraResult)) {
//                    $serializedExtraResult = serialize($extraResult);
//                    $post->setExtra($serializedExtraResult);
//                }

                $id = $this->getPostTable()->save($post);
                $this->getMetaService()->saveMeta('Post', $id, $request->getPost());

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));

                if(!empty($scannedDirectory)) {
                    foreach($scannedDirectory as $file) {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDir.'/'.$file);

                        $postFile = new File();
                        $postFile->setFilename($file);
                        $postFile->setEntityId($id);
                        $postFile->setEntityType('post');
                        $postFile->setMimeType($mimeType);

                        $this->getFileTable()->save($postFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $this->getBlockService()->saveBlocks($id, 'Post', $request->getPost()->toArray(), 'title');

                if(!empty($request->getPost()->toArray()['tag_id'])) {
                    $this->getTagService()->saveTags($request->getPost()->toArray()['tag_id'], $id, 'Post');
                }

                $this->flashMessenger()->addMessage('Wpis został zedytowany poprawnie.');

                return $this->redirect()->toRoute('post', array('category' => $category));
            }
        } else {
            $this->emptyTempDirectory();
        }

        $config = $this->getServiceLocator()->get('config');
//        $extraFields = $config['extra_fields'];

        $fields = array();
//        if(array_key_exists($category, $extraFields['post'])) {
//            $fields = $extraFields['post'][$category];
//        }

//        $extra = $post->getExtra();

//        if(!is_null($extra)) {
//            $extraAsArray = unserialize($extra);
//            $extraAsArrayKeys = array_keys($extraAsArray);
//
//            $tmp = array();
//            foreach($fields as $field) {
//                $attributes = $field['attributes'];
//                $name = $attributes['name'];
//
//                switch($field['type']) {
//                    case 'text':
//                        if(in_array($name, $extraAsArrayKeys)) {
//                            $field['options']['value'] = $extraAsArray[$name];
//                        }
//                        break;
//                    case 'textarea':
//                        if(in_array($name, $extraAsArrayKeys)) {
//                            $field['options']['value'] = $extraAsArray[$name];
//                        }
//                        break;
//                }
//                $tmp[] = $field;
//            }
//            $fields = $tmp;
//        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['post'] = $post;
        $viewParams['extraFields'] = $fields;
        $viewParams['postFiles'] = $postFiles;
        $viewParams['category'] = $category;
        $viewParams['blocks'] = $blocks;
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
        $postFiles = $this->getFileTable()->getBy(array('entity_id' => $id, 'entity_type' => 'Post'));

        if(!$post) {
            return $this->redirect()->toRoute('post', array('category' => $category));
        }
        /* @var $user Users */
        $users = $this->getUsersTable()->getAll();
        $arrUsers = array();

        foreach($users as $user) {
            $arrUsers[$user->getId()] = $user->getName() . ' ' . $user->getSurname();
        }

        $form = new PostForm();
        $form->get('author_id')->setValueOptions($arrUsers);
        $form->bind($post);

        $config = $this->getServiceLocator()->get('config');
//        $extraFields = $config['extra_fields'];

        $fields = array();

//        if(array_key_exists($category, $extraFields['post'])) {
//            $fields = $extraFields['post'][$category];
//        }
//
//        $extra = $post->getExtra();
//
//        if(!is_null($extra)) {
//            $extraAsArray = unserialize($extra);
//            $extraAsArrayKeys = array_keys($extraAsArray);
//
//            $tmp = array();
//            foreach($fields as $field) {
//                $attributes = $field['attributes'];
//                $name = $attributes['name'];
//
//                switch($field['type']) {
//                    case 'text':
//                        if(in_array($name, $extraAsArrayKeys)) {
//                            $field['options']['value'] = $extraAsArray[$name];
//                        }
//                        break;
//                    case 'textarea':
//                        if(in_array($name, $extraAsArrayKeys)) {
//                            $field['options']['value'] = $extraAsArray[$name];
//                        }
//                        break;
//                }
//                $tmp[] = $field;
//            }
//            $fields = $tmp;
//        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['extraFields'] = $fields;
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
                $id = $request->getPost('id');

                if(!is_array($id)) {
                    $id = array($id);
                }

                foreach($id as $oneId) {
                    $postFiles = $this->getFileTable()->getBy(array('entity_type' => 'Post', 'entity_id' => $oneId));

                    if((!empty($postFiles))) {
                        foreach($postFiles as $file) {
                            unlink('./public/files/post/'.$file->getFilename());
                            $this->getFileTable()->deleteFile($file->getId());
                        }
                    }
                }

                $this->getPostTable()->deletePost($id);
                $this->getMetaService()->getMetaTable()->deleteMeta($id, 'Post');

                //$this->flashMessenger()->addMessage('Post został usunięty poprawnie.');
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

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('post_id');
        $category = (int) $this->params()->fromRoute('category');

        if (!$id) {
            return $this->redirect()->toRoute('post-list', array('category' => $category));
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz') {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getPostTable()->changeStatusPost($id, $statusId);

                //$this->flashMessenger()->addMessage('Post został zedytowany poprawnie.');
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

            if($id != 0) {
                $this->getFileTable()->deleteFile($id);
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

    public function uploadFilesMainAction ()
    {
        if (!empty($_FILES)) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];

            $file = explode('.', $targetFile);
            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->destinationUploadDir.$targetFile)) {
                echo $targetFile;
            } else {
                echo 0;
            }

        }
        return $this->response;
    }

    public function sendEmails($emails, $subject, $content)
    {
        $transport = $this->getServiceLocator()->get('mail.transport')->findMailConfig();
        $from = $this->getServiceLocator()->get('mail.transport')->findFromMail();

        $html = new MimePart($content);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($html));

        foreach($emails as $email) {
            $message = new Message();
            $this->getRequest()->getServer();
            $message->addTo($email)
                ->addFrom($from)
                ->setEncoding('UTF-8')
                ->setSubject($subject)
                ->setBody($body);
            $transport->send($message);
        }
    }

    public function emptyTempDirectory()
    {
        $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));

        if(!empty($scannedDirectory)) {
            foreach($scannedDirectory as $file) {
                unlink($this->uploadDir.'/'.$file);
            }
        }
    }

    public function deletePhotoMainAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $filePath = $request->getPost('filePath');

            if(!empty($id)) {
                $this->getFileTable()->deleteFile($id);
                unlink('./public'.$filePath);

            } else {
                unlink('./public'.$filePath);
            }
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
        return $this->response;
    }

    private function checkExtraFields($obj, $arr)
    {
        if(!is_array($arr)) {
            $arr = (array) $arr;
            $formKeys = array_keys($arr);
            $tmp = array();
            foreach($formKeys as $key => $field)
            {
                $tmp[] = substr($field, 3, strlen($field));
            }
            $formKeys = $tmp;
        } else {
            $formKeys = array_keys($arr);
        }

        $result = array();
        foreach($obj as $key => $field) {
            if(!in_array($key, $formKeys)) {
                $result[$key] = $field;
            }
        }

        return $result;
    }
    /**
     * @return \CmsIr\Post\Model\PostTable
     */
    public function getPostTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Model\PostTable');
    }

    /**
     * @return \CmsIr\File\Service\FileService
     */
    public function getFileService()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Service\FileService');
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
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

    /**
     * @return \CmsIr\Meta\Service\MetaService
     */
    public function getMetaService()
    {
        return $this->getServiceLocator()->get('CmsIr\Meta\Service\MetaService');
    }

    /**
     * @return \CmsIr\System\Service\BlockService
     */
    public function getBlockService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\BlockService');
    }

    /**
     * @return \CmsIr\Tag\Service\TagService
     */
    public function getTagService()
    {
        return $this->getServiceLocator()->get('CmsIr\Tag\Service\TagService');
    }
}