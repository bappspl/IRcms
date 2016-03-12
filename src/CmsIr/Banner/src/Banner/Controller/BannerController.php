<?php
namespace CmsIr\Banner\Controller;

use CmsIr\Banner\Form\BannerForm;

use CmsIr\Banner\Form\BannerFormFilter;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class BannerController extends AbstractActionController
{

    public function listAction()
    {
        /* @var $banner \CmsIr\Banner\Entity\Banner */
        $banner = $this->getEm()->find(\CmsIr\Banner\Entity\Banner::ENTITY, 1);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $this->getRequest()->getPost();
            $output = $this->getBannerService()->getDataTables($data);

            $result = new JsonModel($output);
            return $result;
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

        if ($request->isPost()) {
            $form->setInputFilter(new BannerFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getBannerService()->createBanner($form->getData());

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

        /* @var $banner Banner */
        $banner = $this->getBannerService()->getBanner($id);

        if(!$banner) {
            return $this->redirect()->toRoute('banner');
        }

        $form = new BannerForm();
        $form->bind($banner);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new BannerFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getBannerService()->editBanner($form->getData());

                $this->flashMessenger()->addMessage('Baner został edytowana poprawnie.');
                return $this->redirect()->toRoute('banner');
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
        $id = (int) $this->params()->fromRoute('banner_id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('banner');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $ids = $request->getPost('id');

                if(!is_array($ids)) {
                    $ids = array($ids);
                }

                $this->getBannerService()->deleteBanner($ids);

                $this->flashMessenger()->addMessage('Baner został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);

                if($modal == true) {
                    return new JsonModel(array('status' => $id));
                }
            }

            return $this->redirect()->toRoute('banner');
        }
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('banner_id');

        if (!$id) {
            return $this->redirect()->toRoute('banner');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz') {
                $this->getBannerService()->changeStatus($request->getPost());

                $modal = $request->getPost('modal', false);

                if($modal == true) {
                    return new JsonModel(array('status' => 'success'));
                }
            }

            return $this->redirect()->toRoute('page');
        }

        return array();
    }

    public function uploadAction ()
    {
        if (!empty($_FILES)) {
            $filename = $this->getBannerService()->uploadFiles($_FILES);

            echo $filename;

            return $this->response;
        }
    }

    public function deletePhotoAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $filePath = $request->getPost('filePath');
            unlink('./public' . $filePath);

            return new JsonModel(array('status' => 'success'));
        }
    }

    public function changePositionAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $position = $request->getPost('position');

            $this->getBannerService()->changePosition($position);
        }

        return new JsonModel(array('status' => 'success'));
    }

    /**
     * @return \CmsIr\Banner\Service\BannerService
     */
    public function getBannerService()
    {
        return $this->getServiceLocator()->get('CmsIr\Banner\Service\BannerService');
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
}