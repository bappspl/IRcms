<?php

namespace CmsIr\Banner\Controller;

use CmsIr\Banner\Form\BannerForm;
use CmsIr\Banner\Form\BannerFormFilter;
use CmsIr\System\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class BannerController extends AbstractActionController
{
    protected $uploadDir = 'public/files/banner/';
    protected $entity = 'CmsIr\Banner\Entity\Banner';

    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost())
        {

            $data = $this->getRequest()->getPost();
            $columns = array('name');

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

        $viewParams = array();
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function createAction()
    {
        $form = new BannerForm();

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $form->setInputFilter(new BannerFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $banner = new \CmsIr\Banner\Entity\Banner();
                $banner->exchangeArray($form->getData());
                $banner->setSlug(Inflector::slugify($banner->getName()));

                $status = $this->getEm()->find('CmsIr\System\Entity\Status', $banner->getStatus());
                $banner->setStatus($status);

                $this->getEm()->persist($banner);
                $this->getEm()->flush();

                $this->flashMessenger()->addMessage('Baner została dodany poprawnie.');
                return $this->redirect()->toRoute('banner');
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
        $id = $this->params()->fromRoute('banner_id');

        /* @var $banner \CmsIr\Banner\Entity\Banner */
        $banner = $this->getEm()->find($this->entity, $id);
        $banner->setStatus($banner->getStatus()->getId());

        if(!$banner)
        {
            return $this->redirect()->toRoute('banner');
        }

        $form = new BannerForm();
        $form->bind($banner);

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setInputFilter(new BannerFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $filename = $banner->getFilename();

                if(strlen($filename) == 0)
                {
                    $banner->setFilename(null);
                }

                $banner->setSlug(Inflector::slugify($banner->getName()));

                $status = $this->getEm()->find('CmsIr\System\Entity\Status', $banner->getStatus());
                $banner->setStatus($status);

                $this->getEm()->persist($banner);
                $this->getEm()->flush();

                $this->flashMessenger()->addMessage('Baner został edytowana poprawnie.');
                return $this->redirect()->toRoute('banner');
            }
        }

        $viewParams = array();
        $viewParams['id'] = $id;
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('banner_id', 0);

        if (!$id)
        {
            return $this->redirect()->toRoute('banner');
        }

        if ($request->isPost())
        {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak')
            {
                $id = (int) $request->getPost('id');

                $banner = $this->getEm()->find($this->entity, $id);
                $this->getEm()->remove($banner);
                $this->getEm()->flush();

                $this->flashMessenger()->addMessage('Element został usunięty poprawnie.');

                $modal = $request->getPost('modal', false);
                if($modal == true)
                {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('file');
        }

        return array(
            'id'    => $id,
            'page'  => $this->getBannerTable()->getOneBy(array('id' => $id))
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

    public function deletePhotoAction()
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
     * @return \CmsIr\Banner\Service\BannerService
     */
    public function getBannerService()
    {
        return $this->getServiceLocator()->get('CmsIr\Banner\Service\BannerService');
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
}