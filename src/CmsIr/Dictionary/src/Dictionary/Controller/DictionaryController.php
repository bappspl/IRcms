<?php
namespace CmsIr\Dictionary\Controller;

use CmsIr\Dictionary\Form\DictionaryForm;
use CmsIr\Dictionary\Form\DictionaryFormFilter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class DictionaryController extends AbstractActionController
{
    public function listAction()
    {
        $category = $this->params()->fromRoute('category');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('name');

            $listData = $this->getDictionaryTable()->getDictionaryDatatables($columns, $data, $category);

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
        return $viewModel;
    }

    public function createAction()
    {
        $category = $this->params()->fromRoute('category');
        $form = new DictionaryForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new DictionaryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $page = new Dictionary();

                $page->exchangeArray($form->getData());
                $this->getDictionaryTable()->save($page);

                $this->flashMessenger()->addMessage('Element słownika została dodana poprawnie.');

                return $this->redirect()->toRoute('dictionary');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['category'] = $category;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('dictionary_id');

        $dictionary = $this->getDictionaryTable()->getOneBy(array('id' => $id));

        if(!$dictionary) {
            return $this->redirect()->toRoute('dictionary');
        }

        $form = new DictionaryForm();
        $form->bind($dictionary);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new DictionaryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getDictionaryTable()->save($dictionary);

                $this->flashMessenger()->addMessage('Element słownika została edytowana poprawnie.');

                return $this->redirect()->toRoute('dictionary');
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
        $id = (int) $this->params()->fromRoute('dictionary_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('dictionary');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');

                $dictionaryConnected = $this->getDictionaryTable()->getBy(array('id' => $id));
                if(!empty($dictionaryConnected))
                {
                    $dictionaries = array();
                    foreach($dictionaryConnected as $dictionary)
                    {
                        $dictionaries[$dictionary->getId()] = $dictionary->getName();
                    }
                    $this->flashMessenger()->addErrorMessage('Element nie może być usunięty, ponieważ jest przypisana do słownika: ' . implode(', ', $dictionaries) . '.');

                } else
                {
                    $this->getDictionaryTable()->deleteDictionary($id);
                    $this->flashMessenger()->addMessage('Element został usunięty poprawnie.');
                }

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('dictionary');
        }

        return array(
            'id'    => $id,
            'page'  => $this->getDictionaryTable()->getOneBy(array('id' => $id))
        );
    }

    /**
     * @return \CmsIr\Dictionary\Model\DictionaryTable
     */
    public function getDictionaryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Dictionary\Model\DictionaryTable');
    }
}