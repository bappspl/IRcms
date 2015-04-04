<?php
namespace CmsIr\Page\Controller;

use CmsIr\Page\Form\PageForm;
use CmsIr\Page\Form\PageFormFilter;
use CmsIr\Page\Model\Page;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class PageController extends AbstractActionController
{
    protected $uploadDir = 'public/files/page/';

    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('name');

            $listData = $this->getPageTable()->getDatatables($columns,$data);

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

    public function createAction()
    {
        $form = new PageForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new PageFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $page = new Page();

                $page->exchangeArray($form->getData());
                $this->getPageTable()->save($page);

                $this->flashMessenger()->addMessage('Strona została dodana poprawnie.');

                return $this->redirect()->toRoute('page');
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
        $id = $this->params()->fromRoute('page_id');

        $page = $this->getPageTable()->getOneBy(array('id' => $id));

        if(!$page) {
            return $this->redirect()->toRoute('page');
        }

        $form = new PageForm();
        $form->bind($page);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new PageFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getPageTable()->save($page);

                $this->flashMessenger()->addMessage('Strona została edytowana poprawnie.');

                return $this->redirect()->toRoute('page');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('page_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('page');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');
                $this->getPageTable()->deletePage($id);
                $this->flashMessenger()->addMessage('Strona została usunięta poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('page');
        }

        return array(
            'id'    => $id,
            'page' => $this->getPageTable()->getOneBy(array('id' => $id))
        );
    }

    /**
     * @return \CmsIr\Page\Model\PageTable
     */
    public function getPageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Model\PageTable');
    }
}