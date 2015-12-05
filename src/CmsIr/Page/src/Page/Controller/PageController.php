<?php
namespace CmsIr\Page\Controller;

use CmsIr\File\Model\File;
use CmsIr\Menu\Model\MenuItem;
use CmsIr\Menu\Model\MenuNode;
use CmsIr\Page\Form\PageForm;
use CmsIr\Page\Form\PageFormFilter;
use CmsIr\Page\Form\PagePartForm;
use CmsIr\Page\Form\PagePartFormFilter;
use CmsIr\Page\Model\Page;
use CmsIr\Page\Model\PagePart;
use CmsIr\System\Model\Block;
use CmsIr\System\Util\Inflector;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class PageController extends AbstractActionController
{
    protected $uploadDir = 'public/temp_files/page/';
    protected $destinationUploadDir = 'public/files/page/';

    protected $uploadDirPart = 'public/temp_files/page-part/';
    protected $destinationUploadDirPart = 'public/files/page-part/';

    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'id', 'name', 'statusId', 'status', 'id', 'type');

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

                $id = $this->getPageTable()->save($page);
                $this->getMetaService()->saveMeta('Page', $id, $request->getPost());

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory)) {
                    foreach($scannedDirectory as $file) {
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

                $this->getBlockService()->saveBlocks($id, 'Page', $request->getPost()->toArray(), 'title');

                if ($add == 'Tak') {
                    $parentNodeId = $request->getPost('menu');

                    $menuNode = new MenuNode();
                    $menuNode->setTreeId(1);
                    $menuNode->setIsVisible(1);
                    $menuNode->setProviderType('Page');
                    $menuNode->setPosition(0);

                    if ($parentNodeId == 0) {
                        $menuNode->setDepth(0);
                    } elseif ($parentNodeId > 0) {
                        $menuNode->setDepth(1);
                        $menuNode->setParentId($parentNodeId);
                    }

                    $nodeId = $this->getMenuService()->saveMenuNode($menuNode);

                    $menuItem = new MenuItem();
                    $menuItem->setNodeId($nodeId);
                    $menuItem->setLabel($page->getName());

                    /* @var $pageBlock Block */
                    $pageBlock = $this->getBlockTable()->getOneBy(array('entity_type' => 'Page', 'language_id' => 1, 'name' => 'url'));

                    $menuItem->setUrl('/strona/' . $pageBlock->getValue());
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

        if(!$page)  {
            return $this->redirect()->toRoute('page');
        }

        $pageFiles = $this->getFileTable()->getBy(array('entity_id' => $id, 'entity_type' => 'page'));

        $blocks = $this->getBlockService()->getBlocks($page, 'Page');

        $form = new PageForm();
        $form->bind($page);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new PageFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $id = $this->getPageTable()->save($page);
                $this->getMetaService()->saveMeta('Page', $id, $request->getPost());

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory)) {
                    foreach($scannedDirectory as $file) {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDir.'/'.$file);

                        $postFile = new File();
                        $postFile->setFilename($file);
                        $postFile->setEntityId($id);
                        $postFile->setEntityType('Page');
                        $postFile->setMimeType($mimeType);

                        $this->getFileTable()->save($postFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $this->getBlockService()->saveBlocks($id, 'Page', $request->getPost()->toArray(), 'title');

                $this->flashMessenger()->addMessage('Strona została edytowana poprawnie.');

                return $this->redirect()->toRoute('page');
            }
        }

        $viewParams = array();
        $viewParams['page'] = $page;
        $viewParams['form'] = $form;
        $viewParams['pageFiles'] = $pageFiles;
        $viewParams['blocks'] = $blocks;
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

                /* @var $page Page */
                $page = $this->getPageTable()->getOneBy(array('id' => $id));
                $url = $page->getUrl();
                $menuItem = $this->getMenuItemTable()->getOneBy(array('url' => '/strona/' . $url));

                /* @var $menuItem MenuItem */
                if($menuItem) {
                    $menuNodeId = $menuItem->getNodeId();
                    $this->getMenuItemTable()->deleteMenuItemByNodeId($menuNodeId);
                    $this->getMenuNodeTable()->deleteMenuNode($menuNodeId);
                }

                if(!is_array($id)) {
                    $id = array($id);
                }

                foreach($id as $oneId) {
                    $pageFiles = $this->getFileTable()->getBy(array('entity_type' => 'page', 'entity_id' => $oneId));

                    if((!empty($pageFiles))) {
                        foreach($pageFiles as $file) {
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

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz') {
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

    public function uploadFilesAction ()
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

    public function deletePhotoAction()
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

    public function partAction()
    {
        $pageId = (int) $this->params()->fromRoute('page_id');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'id', 'position');

            $listData = $this->getPagePartTable()->getDatatables($columns, $data, $pageId);

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
        $viewParams['pageId'] = $pageId;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function createPartAction()
    {
        $pageId = (int) $this->params()->fromRoute('page_id');

        $form = new PagePartForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new PagePartFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $pagePart = new PagePart();
                $pagePart->exchangeArray($form->getData());

                $pagePart->setPageId($pageId);

                $id = $this->getPagePartTable()->save($pagePart);

                $scannedDirectory = array_diff(scandir($this->uploadDirPart), array('..', '.'));
                if(!empty($scannedDirectory)) {
                    foreach($scannedDirectory as $file) {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDirPart.'/'.$file);

                        $postFile = new File();
                        $postFile->setFilename($file);
                        $postFile->setEntityId($id);
                        $postFile->setEntityType('PagePart');
                        $postFile->setMimeType($mimeType);

                        $this->getFileTable()->save($postFile);

                        rename($this->uploadDirPart.'/'.$file, $this->destinationUploadDirPart.'/'.$file);
                    }
                }

                $this->getBlockService()->saveBlocks($id, 'PagePart', $request->getPost()->toArray(), 'title');

                $this->flashMessenger()->addMessage('Sekcja została dodana poprawnie.');

                return $this->redirect()->toRoute('page/part', array('page_id' => $pageId));
            }
        }

        $menuNodes = $this->getMenuService()->findMenuItemsForPage();

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['menuNodes'] = $menuNodes;
        $viewParams['pageId'] = $pageId;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editPartAction()
    {
        $pageId = $this->params()->fromRoute('page_id');
        $pagePartId = $this->params()->fromRoute('page_part_id');

        /* @var $pagePart PagePart */
        $pagePart = $this->getPagePartTable()->getOneBy(array('id' => $pagePartId));

        if(!$pagePart)  {
            return $this->redirect()->toRoute('page/part', array('page_id' => $pageId));
        }

        $pagePartFiles = $this->getFileTable()->getBy(array('entity_id' => $pagePartId, 'entity_type' => 'PagePart'));

        $blocks = $this->getBlockService()->getBlocks($pagePart, 'PagePart');

        $form = new PagePartForm();
        $form->bind($pagePart);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new PagePartFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $id = $this->getPagePartTable()->save($pagePart);
                $this->getMetaService()->saveMeta('Page', $id, $request->getPost());

                $scannedDirectory = array_diff(scandir($this->uploadDirPart), array('..', '.'));
                if(!empty($scannedDirectory)) {
                    foreach($scannedDirectory as $file) {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDirPart.'/'.$file);

                        $postFile = new File();
                        $postFile->setFilename($file);
                        $postFile->setEntityId($id);
                        $postFile->setEntityType('PagePart');
                        $postFile->setMimeType($mimeType);

                        $this->getFileTable()->save($postFile);

                        rename($this->uploadDirPart.'/'.$file, $this->destinationUploadDirPart.'/'.$file);
                    }
                }

                $this->getBlockService()->saveBlocks($id, 'PagePart', $request->getPost()->toArray(), 'title');

                $this->flashMessenger()->addMessage('Sekcja została edytowana poprawnie.');

                return $this->redirect()->toRoute('page/part', array('page_id' => $pageId));
            }
        }

        $viewParams = array();
        $viewParams['page'] = $pagePart;
        $viewParams['form'] = $form;
        $viewParams['pageFiles'] = $pagePartFiles;
        $viewParams['blocks'] = $blocks;
        $viewParams['pageId'] = $pageId;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deletePartAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('page_part_id', 0);
        $pageId = (int) $this->params()->fromRoute('page_id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('page');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');

                if(!is_array($id)) {
                    $id = array($id);
                }

                foreach($id as $oneId) {
                    $pageFiles = $this->getFileTable()->getBy(array('entity_type' => 'PagePart', 'entity_id' => $oneId));

                    if((!empty($pageFiles))) {
                        foreach($pageFiles as $file) {
                            unlink('./public/files/page-part/'.$file->getFilename());
                            $this->getFileTable()->deleteFile($file->getId());
                        }
                    }
                }

                $this->getPagePartTable()->deletePagePart($id);
                $this->flashMessenger()->addMessage('Sekcja została usunięta poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('page/part', array('page_id' => $pageId));
        }

        return array(
            'id'    => $id,
            'page' => $this->getPageTable()->getOneBy(array('id' => $id))
        );
    }

    public function uploadFilesMainPartAction ()
    {
        if (!empty($_FILES)) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];

            $file = explode('.', $targetFile);
            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->destinationUploadDirPart.$targetFile)) {
                echo $targetFile;
            } else {
                echo 0;
            }
        }
        return $this->response;
    }

    public function uploadFilesPartAction ()
    {
        if (!empty($_FILES)) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];
            $file = explode('.', $targetFile);

            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->uploadDirPart.$targetFile)) {
                echo $targetFile;
            } else {
                echo 0;
            }

        }
        return $this->response;
    }

    public function changePositionAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $position = $request->getPost('position');

            $this->getPagePartTable()->changePosition($position);
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
        return $this->response;
    }

    public function getPartsAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $id = $request->getPost('id');

            $parts = $this->getPagePartTable()->getBy(array('page_id' => $id), 'position ASC');

            $htmlViewPart = new ViewModel();
            $htmlViewPart->setTerminal(true)
                ->setTemplate('partial/parts')
                ->setVariables(array(
                    'parts' => $parts
                ));

            $htmlOutput = $this->getServiceLocator()
                ->get('viewrenderer')
                ->render($htmlViewPart);

            $jsonObject = Json::encode($htmlOutput, true);
            echo $jsonObject;
            return $this->response;
        }

        $jsonObject = Json::encode($params['error'] = 'error', true);
        echo $jsonObject;
        return $this->response;
    }

    public function orderPartsAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $pos = $request->getPost('pos');

            $this->getPagePartTable()->changePosition($pos);

            $jsonObject = Json::encode('ok', true);
            echo $jsonObject;
            return $this->response;
        }

        $jsonObject = Json::encode($params['error'] = 'error', true);
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
     * @return \CmsIr\Menu\Model\MenuItemTable
     */
    public function getMenuItemTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Menu\Model\MenuItemTable');
    }

    /**
     * @return \CmsIr\Menu\Model\MenuNodeTable
     */
    public function getMenuNodeTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Menu\Model\MenuNodeTable');
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

    /**
     * @return \CmsIr\System\Service\BlockService
     */
    public function getBlockService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\BlockService');
    }

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
    }

    /**
     * @return \CmsIr\Page\Model\PagePartTable
     */
    public function getPagePartTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Model\PagePartTable');
    }
}