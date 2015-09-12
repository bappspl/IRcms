<?php
namespace CmsIr\Tag\Controller;

use CmsIr\Tag\Form\TagForm;
use CmsIr\Tag\Form\TagFormFilter;
use CmsIr\Tag\Model\Tag;
use CmsIr\Dictionary\Form\DictionaryForm;
use CmsIr\Dictionary\Form\DictionaryFormFilter;
use CmsIr\Dictionary\Model\Dictionary;
use CmsIr\System\Model\MenuTable;
use CmsIr\System\Util\Inflector;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\Navigation\Menu;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class TagController extends AbstractActionController
{
    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'id');

            $listData = $this->getTagTable()->getTagDatatables($columns, $data);

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
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function createAction()
    {
        $form = new TagForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new DictionaryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $tag = new Tag();

                $tag->exchangeArray($form->getData());
                $id = $this->getTagTable()->save($tag);

                $this->getBlockService()->saveBlocks($id, 'Tag', $request->getPost()->toArray(), 'title');

                $this->flashMessenger()->addMessage('Tag został dodany poprawnie.');

                return $this->redirect()->toRoute('tag');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('tag_id');

        $tag = $this->getTagTable()->getOneBy(array('id' => $id));

        if(!$tag)
        {
            return $this->redirect()->toRoute('tag');
        }

        $blocks = $this->getBlockService()->getBlocks($tag, 'Tag');

        $form = new TagForm();
        $form->bind($tag);

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $form->setInputFilter(new TagFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $this->getTagTable()->save($tag);
                $this->getBlockService()->saveBlocks($id, 'Tag', $request->getPost()->toArray(), 'title');

                $this->flashMessenger()->addMessage('Tag został edytowany poprawnie.');

                return $this->redirect()->toRoute('tag');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['blocks'] = $blocks;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('tag_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('tag');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');

                if(!is_array($id))
                {
                    $id = array($id);
                }

                $this->getTagTable()->deleteTag($id);
                $this->flashMessenger()->addMessage('Element został usunięty poprawnie.');

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('tag');
        }

        return array(
            'id'    => $id,
            'page'  => $this->getTagTable()->getOneBy(array('id' => $id))
        );
    }

    /**
     * @return \CmsIr\Tag\Model\TagTable
     */
    public function getTagTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Tag\Model\TagTable');
    }

    /**
     * @return \CmsIr\System\Service\BlockService
     */
    public function getBlockService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\BlockService');
    }
}