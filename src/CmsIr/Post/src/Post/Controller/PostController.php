<?php
namespace CmsIr\Post\Controller;

use CmsIr\Post\Form\PostForm;
use CmsIr\Post\Form\PostFormFilter;
use CmsIr\Post\Model\Post;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;

class PostController extends AbstractActionController
{
    protected $usersTable;
    protected $uploadDir = 'public/files/post/';
    protected $appName = 'Cms-ir';

    public function postListAction()
    {
        $category = $this->params()->fromRoute('category');
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array( 'name', 'url');

            $listData = $this->getPostTable()->getPostDatatables($columns, $data, $category);
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

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new PostFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $post = new Post();
                $post->exchangeArray($form->getData());
                $post->setCategory($category);
                $this->getPostTable()->save($post);

                $this->flashMessenger()->addMessage('Wpis został utworzony poprawnie.');

                return $this->redirect()->toRoute('post-list', array('category' => $category));
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

        /**
         * @var $post Post
         */
        $post = $this->getPostTable()->getOneBy(array('id' => $id));

        if(!$post) {
            return $this->redirect()->toRoute('post-list', array('category' => $category));
        }

        $form = new PostForm();
        $form->bind($post);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new PostFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $post->setCategory($category);
                $this->getPostTable()->save($post);

                $this->flashMessenger()->addMessage('Wpis został zedytowany poprawnie.');

                return $this->redirect()->toRoute('post-list', array('category' => $category));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
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

        if(!$post) {
            return $this->redirect()->toRoute('post-list', array('category' => $category));
        }

        $form = new PostForm();
        $form->bind($post);

        $viewParams = array();
        $viewParams['form'] = $form;
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

    /**
     * @return \CmsIr\Post\Model\PostTable
     */
    public function getPostTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Model\PostTable');
    }
}