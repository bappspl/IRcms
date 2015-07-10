<?php
namespace CmsIr\Page\Controller;

use Doctrine\ORM\EntityManager;
use CmsIr\Page\Form\PageForm;
use CmsIr\Page\Form\PageFormFilter;
use CmsIr\System\Util\Inflector;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class PageController extends AbstractActionController
{
    protected $uploadDir = 'public/temp_files/page/';
    protected $destinationUploadDir = 'public/files/page/';
    protected $entity = 'CmsIr\Page\Entity\Page';

    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'statusId', 'status', 'id');

            $listData = $this->getEm()->getRepository($this->entity)->getDatatables($columns, $data);

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
        $statuses = $this->getEm()->getRepository('CmsIr\System\Entity\Status')->findBy(array('slug' => array('active', 'inactive')), array('id' =>  'DESC'));

        $form = new PageForm($statuses);

        $request = $this->getRequest();

        if ($request->isPost())
        {

            $form->setInputFilter(new PageFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $page = new \CmsIr\Page\Entity\Page();

                $page->exchangeArray($form->getData());

                if($page->getUrl() === null)
                {
                    $page->setUrl(Inflector::slugify($page->getName()));
                }

                $status = $this->getEm()->find('CmsIr\System\Entity\Status', $page->getStatus());
                $page->setStatus($status);

                $this->getEm()->persist($page);
                $this->getEm()->flush();

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $file)
                    {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDir.'/'.$file);

                        $pageFile = new \CmsIr\File\Entity\File();
                        $pageFile->setFilename($file);
                        $pageFile->setPage($page);
                        $pageFile->setEntityType('page');
                        $pageFile->setMimeType($mimeType);

                        $this->getEm()->persist($pageFile);
                        $this->getEm()->flush();

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

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

        /* @var $page \CmsIr\Page\Entity\Page */
        $page = $this->getEm()->find($this->entity, $id);

        if(!$page)
        {
            return $this->redirect()->toRoute('page');
        }

        $pageFiles = $page->getFiles();

        $form = new PageForm();
        $form->bind($page);

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $form->setInputFilter(new PageFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $filename = $page->getFilename();

                if(strlen($filename) == 0)
                {
                    $page->setFilename(null);
                }

                if($page->getUrl() === null)
                {
                    $page->setUrl(Inflector::slugify($page->getName()));
                }

                $status = $this->getEm()->find('CmsIr\System\Entity\Status', $page->getStatus());
                $page->setStatus($status);

                $this->getEm()->persist($page);
                $this->getEm()->flush();

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $file)
                    {
                        $mimeType = $this->getFileService()->getMimeContentType($this->uploadDir.'/'.$file);

                        $pageFile = new \CmsIr\File\Entity\File();
                        $pageFile->setFilename($file);
                        $pageFile->setPage($page);
                        $pageFile->setEntityType('page');
                        $pageFile->setMimeType($mimeType);

                        $this->getEm()->persist($pageFile);
                        $this->getEm()->flush();

                        rename($this->uploadDir.'/'.$file, $this->destinationUploadDir.'/'.$file);
                    }
                }

                $this->flashMessenger()->addMessage('Strona została edytowana poprawnie.');

                return $this->redirect()->toRoute('page');
            }
        }

        $viewParams = array();
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

        if (!$id)
        {
            return $this->redirect()->toRoute('page');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');

                /* @var $page \CmsIr\Page\Entity\Page */
                $page = $this->getEm()->find($this->entity, $id);

                $files = $page->getFiles();

                foreach($files as $file)
                {
                    $this->getEm()->remove($file);
                    $this->getEm()->flush();
                }

                $this->getEm()->remove($page);
                $this->getEm()->flush();

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
            'id'    => $id
        );
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
        if ($request->isPost())
        {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $filePath = $request->getPost('filePath');

            if(!empty($id))
            {
                $file = $this->getEm()->find('\CmsIr\File\Entity\File', $id);
                $this->getEm()->remove($file);
                $this->getEm()->flush();

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
        if ($request->isPost())
        {
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
     * @return \CmsIr\File\Service\FileService
     */
    public function getFileService()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Service\FileService');
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
}