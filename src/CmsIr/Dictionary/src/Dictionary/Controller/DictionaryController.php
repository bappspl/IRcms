<?php
namespace CmsIr\Dictionary\Controller;

use CmsIr\Dictionary\Form\DictionaryForm;
use CmsIr\Dictionary\Form\DictionaryFormFilter;
use CmsIr\Dictionary\Model\Dictionary;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class DictionaryController extends AbstractActionController
{
    protected $uploadDir = 'public/files/dictionary/';

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
                $dictionary = new Dictionary();

                $dictionary->exchangeArray($form->getData());

                $dictionary->setCategory($category);
                $this->getDictionaryTable()->save($dictionary);

                $this->flashMessenger()->addMessage('Element słownika została dodana poprawnie.');

                return $this->redirect()->toRoute('dictionary', array('category' => $category));
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
        $category = $this->params()->fromRoute('category');

        $dictionary = $this->getDictionaryTable()->getOneBy(array('id' => $id));

        if(!$dictionary) {
            return $this->redirect()->toRoute('dictionary', array('category' => $category));
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

                return $this->redirect()->toRoute('dictionary', array('category' => $category));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['category'] = $category;
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

                $this->getDictionaryTable()->deleteDictionary($id);
                $this->flashMessenger()->addMessage('Element został usunięty poprawnie.');

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

    /**
     * @return \CmsIr\Dictionary\Model\DictionaryTable
     */
    public function getDictionaryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Dictionary\Model\DictionaryTable');
    }
}