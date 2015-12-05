<?php
namespace CmsIr\File\Controller;

use CmsIr\File\Form\FileForm;
use CmsIr\File\Form\FileFormFilter;
use CmsIr\File\Form\GalleryForm;
use CmsIr\File\Form\GalleryFormFilter;
use CmsIr\File\Model\File;
use CmsIr\File\Model\Gallery;
use CmsIr\System\Util\Inflector;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class GalleryController extends AbstractActionController
{
    protected $uploadDir = 'public/temp_files/gallery/';
    protected $destinationUploadDir = 'public/files/gallery/';

    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'statusId', 'status', 'id');

            $listData = $this->getGalleryTable()->getDatatables($columns,$data);

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
        $statuses = $this->getStatusService()->findAsAssocArray();
        $categories = $this->getCategoryService()->findAsAssocArray('gallery');

        $form = new GalleryForm($statuses, $categories);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new GalleryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $gallery = new Gallery();
                $gallery->exchangeArray($form->getData());
                $gallery->setSlug(Inflector::slugify($gallery->getName()));

                $id = $this->getGalleryTable()->save($gallery);

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory)) {
                    foreach($scannedDirectory as $file) {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDir.'/'.$file);

                        $postFile = new File();
                        $postFile->setFilename($file);
                        $postFile->setEntityId($id);
                        $postFile->setEntityType('gallery');
                        $postFile->setMimeType($mimeType);

                        $this->getFileTable()->save($postFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $this->getBlockService()->saveBlocks($id, 'Gallery', $request->getPost()->toArray(), 'title');

                $this->flashMessenger()->addMessage('Galeria została dodana poprawnie.');
                return $this->redirect()->toRoute('gallery');
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
        $id = $this->params()->fromRoute('gallery_id');

        /* @var $gallery Gallery */
        $gallery = $this->getGalleryTable()->getOneBy(array('id' => $id));
        $galleryFiles = $this->getFileTable()->getBy(array('entity_id' => $id, 'entity_type' => 'gallery'));

        if(!$gallery) {
            return $this->redirect()->toRoute('gallery');
        }

        $blocks = $this->getBlockService()->getBlocks($gallery, 'Gallery');

        $statuses = $this->getStatusService()->findAsAssocArray();
        $categories = $this->getCategoryService()->findAsAssocArray('gallery');

        $form = new GalleryForm($statuses, $categories);
        $form->bind($gallery);

        $request = $this->getRequest();

        if ($request->isPost())  {
            $form->setInputFilter(new GalleryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                if(strlen($gallery->getUrl()) == 0) {
                    $gallery->setUrl(Inflector::slugify($gallery->getName()));
                }

                $gallery->setSlug(Inflector::slugify($gallery->getName()));

                $id = $this->getGalleryTable()->save($gallery);

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory)) {
                    foreach($scannedDirectory as $file) {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDir.'/'.$file);

                        $postFile = new File();
                        $postFile->setFilename($file);
                        $postFile->setEntityId($id);
                        $postFile->setEntityType('gallery');
                        $postFile->setMimeType($mimeType);

                        $this->getFileTable()->save($postFile);

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $this->getBlockService()->saveBlocks($id, 'Gallery', $request->getPost()->toArray(), 'title');

                $this->flashMessenger()->addMessage('Galeria została edytowana poprawnie.');
                return $this->redirect()->toRoute('gallery');
            }
        } else {
            $path = $this->uploadDir . '*';
            array_map('unlink', glob($path));
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['galleryFiles'] = $galleryFiles;
        $viewParams['blocks'] = $blocks;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('gallery_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('gallery');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');

                if(!is_array($id)) {
                    $id = array($id);
                }

                foreach($id as $oneId) {
                    $galleryFiles = $this->getFileTable()->getBy(array('entity_type' => 'gallery', 'entity_id' => $oneId));

                    if((!empty($galleryFiles))) {
                        foreach($galleryFiles as $file) {
                            unlink('./public/files/gallery/'.$file->getFilename());
                            $this->getFileTable()->deleteFile($file->getId());
                        }
                    }
                }

                $this->getGalleryTable()->deleteGallery($id);
                $this->flashMessenger()->addMessage('Galeria została usunięta poprawnie.');
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
            'page' => $this->getGalleryTable()->getOneBy(array('id' => $id))
        );
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('gallery_id');

        if (!$id) {
            return $this->redirect()->toRoute('gallery');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz') {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getGalleryTable()->changeStatusGallery($id, $statusId);

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('gallery');
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

    /**
     * @return \CmsIr\File\Model\GalleryTable
     */
    public function getGalleryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\GalleryTable');
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
    }

    /**
     * @return \CmsIr\File\Service\FileService
     */
    public function getFileService()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Service\FileService');
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
     * @return \CmsIr\System\Service\StatusService
     */
    public function getStatusService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\StatusService');
    }

    /**
     * @return \CmsIr\Category\Service\CategoryService
     */
    public function getCategoryService()
    {
        return $this->getServiceLocator()->get('CmsIr\Category\Service\CategoryService');
    }
}