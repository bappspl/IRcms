<?php
namespace CmsIr\Dictionary\Controller;

use CmsIr\Dictionary\Form\DictionaryForm;
use CmsIr\Dictionary\Form\DictionaryFormFilter;
use CmsIr\Dictionary\Model\Dictionary;
use CmsIr\System\Util\Inflector;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;
use Doctrine\ORM\EntityManager;

class DictionaryController extends AbstractActionController
{
    protected $uploadDir = 'public/files/dictionary/';
    protected $entity = 'CmsIr\Dictionary\Entity\Dictionary';

    public function listAction()
    {
        $category = $this->params()->fromRoute('category');

        $request = $this->getRequest();
        if ($request->isPost())
        {

            $data = $this->getRequest()->getPost();
            $columns = array('name');

            $listData = $this->getEm()->getRepository($this->entity)->getDatatables($columns, $data, $category);

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

        if ($request->isPost())
        {
            $form->setInputFilter(new DictionaryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $dictionary = new \CmsIr\Dictionary\Entity\Dictionary();

                $dictionary->exchangeArray($form->getData());

//                $name = $form->getData();
//                $name = $name['name'];

//                $this->createPostCategoryMenuItem($name);

                $dictionary->setCategory($category);
                $this->getEm()->persist($dictionary);
                $this->getEm()->flush($dictionary);

                $this->flashMessenger()->addMessage('Element słownika został dodany poprawnie.');

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

        $dictionary = $this->getEm()->find($this->entity, $id);

        if(!$dictionary)
        {
            return $this->redirect()->toRoute('dictionary', array('category' => $category));
        }

        $form = new DictionaryForm();
        $form->bind($dictionary);

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $form->setInputFilter(new DictionaryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                /* @var $dictionary \CmsIr\Dictionary\Entity\Dictionary */
                $filename = $dictionary->getFilename();

                if(strlen($filename) == 0)
                {
                    $dictionary->setFilename(null);
                }

                $this->getEm()->persist($dictionary);
                $this->getEm()->flush();

                $this->flashMessenger()->addMessage('Element słownika został edytowany poprawnie.');

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

                $dictionary = $this->getEm()->find($this->entity, $id);
                $this->getEm()->remove($dictionary);
                $this->getEm()->flush();

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

            if(move_uploaded_file($tempFile,$this->uploadDir.$targetFile))
            {
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
        if ($request->isPost())
        {
            $filePath = $request->getPost('filePath');

            unlink('./public'.$filePath);
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
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

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
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
}