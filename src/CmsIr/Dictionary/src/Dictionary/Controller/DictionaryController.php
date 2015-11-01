<?php
namespace CmsIr\Dictionary\Controller;

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

class DictionaryController extends AbstractActionController
{
    protected $destinationUploadDir = 'public/files/dictionary/';

    public function listAction()
    {
        $category = $this->params()->fromRoute('category');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'id');

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
        $categories = $this->getCategoryService()->findAsAssocArray();

        $category = $this->params()->fromRoute('category');
        $form = new DictionaryForm($categories);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new DictionaryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $dictionary = new Dictionary();

                $dictionary->exchangeArray($form->getData());

                $name = $form->getData();
                $name = $name['name'];

//                $this->createPostCategoryMenuItem($name);

                $dictionary->setCategory($category);

                $id = $this->getDictionaryTable()->save($dictionary);

                $this->getBlockService()->saveBlocks($id, 'Dictionary', $request->getPost()->toArray(), 'title');

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
        $categories = $this->getCategoryService()->findAsAssocArray();

        $id = $this->params()->fromRoute('dictionary_id');
        $category = $this->params()->fromRoute('category');

        $dictionary = $this->getDictionaryTable()->getOneBy(array('id' => $id));

        if(!$dictionary) {
            return $this->redirect()->toRoute('dictionary', array('category' => $category));
        }

        $blocks = $this->getBlockService()->getBlocks($dictionary, 'Dictionary');

        $form = new DictionaryForm($categories);
        $form->bind($dictionary);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new DictionaryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getDictionaryTable()->save($dictionary);
                $this->getBlockService()->saveBlocks($id, 'Dictionary', $request->getPost()->toArray(), 'title');

                $this->flashMessenger()->addMessage('Element słownika została edytowana poprawnie.');

                return $this->redirect()->toRoute('dictionary', array('category' => $category));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['category'] = $category;
        $viewParams['blocks'] = $blocks;
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
                $id = $request->getPost('id');

                if(!is_array($id)) {
                    $id = array($id);
                }

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

            if(move_uploaded_file($tempFile,$this->destinationUploadDir.$targetFile)) {
                echo $targetFile;
            } else {
                echo 0;
            }

        }
        return $this->response;
    }

    private function createPostCategoryMenuItem($name)
    {
        $backendMenuItem = array(
            'name' => 'lista-' . Inflector::slugify($name),
            'label' => $name,
            'route' => 'post',
            'visible_in_primary' => 1,
            'parent_id' => 10,
            'params' => "'category' => '" . Inflector::slugify($name) . "'"
        );
        $backendMenuItem1 = array(
            'name' => 'tworzenie-' . Inflector::slugify($name),
            'label' => $name,
            'route' => 'post/create',
            'visible_in_primary' => '',
            'parent_id' => 10,
            'params' => "'category' => '" . Inflector::slugify($name) . "'"
        );
        $backendMenuItem2 = array(
            'name' => 'edycja-' . Inflector::slugify($name),
            'label' => $name,
            'route' => 'post/edit',
            'visible_in_primary' => '',
            'parent_id' => 10,
            'params' => "'category' => '" . Inflector::slugify($name) . "'"
        );
        $backendMenuItem3 = array(
            'name' => 'podglad-' . Inflector::slugify($name),
            'label' => $name,
            'route' => 'post/preview',
            'visible_in_primary' => '',
            'parent_id' => 10,
            'params' => "'category' => '" . Inflector::slugify($name) . "'"
        );

        $this->getMenuTable()->save($backendMenuItem);
        $this->getMenuTable()->save($backendMenuItem1);
        $this->getMenuTable()->save($backendMenuItem2);
        $this->getMenuTable()->save($backendMenuItem3);
    }

    public function deletePhotoAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $filePath = $request->getPost('filePath');

            unlink('./public'.$filePath);
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
        return $this->response;
    }

    /**
     * @return \CmsIr\Dictionary\Model\DictionaryTable
     */
    public function getDictionaryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Dictionary\Model\DictionaryTable');
    }

    /**
     * @return \CmsIr\System\Model\MenuTable
     */
    public function getMenuTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\MenuTable');
    }

    /**
     * @return \CmsIr\System\Service\BlockService
     */
    public function getBlockService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\BlockService');
    }

    /**
     * @return \CmsIr\Category\Service\CategoryService
     */
    public function getCategoryService()
    {
        return $this->getServiceLocator()->get('CmsIr\Category\Service\CategoryService');
    }
}