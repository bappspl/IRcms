<?php
namespace CmsIr\Page\Controller;

use CmsIr\File\Model\File;
use CmsIr\Menu\Model\MenuItem;
use CmsIr\Menu\Model\MenuNode;
use CmsIr\Page\Form\PageForm;
use CmsIr\Page\Form\PageFormFilter;
use CmsIr\Page\Model\Page;
use CmsIr\System\Util\Inflector;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class PageController extends AbstractActionController
{
    protected $uploadDir = 'public/temp_files/page/';
    protected $destinationUploadDir = 'public/files/page/';

    public function getMailConfigTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\MailConfigTable');
    }

    public function listAction()
    {
        //$options = $this->getMailConfigTable()->generateMailConfigArray();
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'statusId', 'status', 'id');

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
                $page->setSlug(Inflector::slugify($page->getName()));

                $id = $this->getPageTable()->save($page);
                $this->getMetaService()->saveMeta('Page', $id, $request->getPost());

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $file)
                    {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDir.'/'.$file);

                        $postFile = new File();
                        $postFile->setFilename($file);
                        $postFile->setEntityId($id);
                        $postFile->setEntityType('page');
                        $postFile->setMimeType($mimeType);

                        $this->getFileTable()->save($postFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $add = $request->getPost('add', 'Anuluj');

                if ($add == 'Tak')
                {
                    $parentNodeId = $request->getPost('menu');

                    $menuNode = new MenuNode();
                    $menuNode->setTreeId(1);
                    $menuNode->setIsVisible(1);
                    $menuNode->setProviderType('page');
                    $menuNode->setPosition(0);

                    if ($parentNodeId == 0)
                    {
                        $menuNode->setDepth(0);
                    } elseif ($parentNodeId > 0)
                    {
                        $menuNode->setDepth(1);
                        $menuNode->setParentId($parentNodeId);
                    }

                    $nodeId = $this->getMenuService()->saveMenuNode($menuNode);

                    $menuItem = new MenuItem();
                    $menuItem->setNodeId($nodeId);
                    $menuItem->setLabel($page->getName());
                    $menuItem->setUrl('/strona/'.$page->getUrl());
                    $menuItem->setPosition(0);

                    $this->getMenuService()->saveMenuItem($menuItem);
                }

                $this->flashMessenger()->addMessage('Strona została dodana poprawnie.');

                return $this->redirect()->toRoute('page');
            }
        }

        $menuNodes = $this->getMenuService()->findMenuItemsForPage();

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['menuNodes'] = $menuNodes;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('page_id');

        /* @var $page Page */
        $page = $this->getPageTable()->getOneBy(array('id' => $id));

        if(!$page) {
            return $this->redirect()->toRoute('page');
        }

        $pageFiles = $this->getFileTable()->getBy(array('entity_id' => $id, 'entity_type' => 'page'));

        $form = new PageForm();
        $form->bind($page);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new PageFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $page->setSlug(Inflector::slugify($page->getName()));
                $id = $this->getPageTable()->save($page);
                $this->getMetaService()->saveMeta('Page', $id, $request->getPost());

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $file)
                    {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDir.'/'.$file);

                        $postFile = new File();
                        $postFile->setFilename($file);
                        $postFile->setEntityId($id);
                        $postFile->setEntityType('page');
                        $postFile->setMimeType($mimeType);

                        $this->getFileTable()->save($postFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $this->flashMessenger()->addMessage('Strona została edytowana poprawnie.');

                return $this->redirect()->toRoute('page');
            }
        }

        $viewParams = array();
        $viewParams['page'] = $page;
        $viewParams['form'] = $form;
        $viewParams['pageFiles'] = $pageFiles;
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
                $id = $request->getPost('id');

                if(!is_array($id))
                {
                    $id = array($id);
                }

                foreach($id as $oneId)
                {
                    $pageFiles = $this->getFileTable()->getBy(array('entity_type' => 'page', 'entity_id' => $oneId));

                    if((!empty($pageFiles)))
                    {
                        foreach($pageFiles as $file)
                        {
                            unlink('./public/files/page/'.$file->getFilename());
                            $this->getFileTable()->deleteFile($file->getId());
                        }
                    }
                }

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

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('page_id');

        if (!$id) {
            return $this->redirect()->toRoute('page');
        }

        if ($request->isPost())
        {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz')
            {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getPageTable()->changeStatusPage($id, $statusId);

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('page');
        }

        return array();
    }

    public function uploadFilesMainAction ()
    {
        if (!empty($_FILES))
        {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];

            $file = explode('.', $targetFile);
            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->destinationUploadDir.$targetFile))
            {
                echo $targetFile;
            } else
            {
                echo 0;
            }
        }
        return $this->response;
    }

    public function uploadFilesAction ()
    {
        if (!empty($_FILES))
        {
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
            } else
            {
                echo 0;
            }

        }
        return $this->response;
    }

    public function deletePhotoAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $filePath = $request->getPost('filePath');

            if(!empty($id))
            {
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

    public function deletePhotoMainAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $filePath = $request->getPost('filePath');

            if(!empty($id))
            {
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

    /**
     * @return \CmsIr\Page\Model\PageTable
     */
    public function getPageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Model\PageTable');
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
     * @return \CmsIr\Menu\Service\MenuService
     */
    public function getMenuService()
    {
        return $this->getServiceLocator()->get('CmsIr\Menu\Service\MenuService');
    }

    /**
     * @return \CmsIr\Meta\Service\MetaService
     */
    public function getMetaService()
    {
        return $this->getServiceLocator()->get('CmsIr\Meta\Service\MetaService');
    }
}