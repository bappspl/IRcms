<?php
namespace CmsIr\Video\Controller;

use CmsIr\System\Util\Inflector;
use CmsIr\Video\Form\VideoForm;
use CmsIr\Video\Form\VideoFormFilter;
use CmsIr\Video\Model\Video;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class VideoController extends AbstractActionController
{
    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'statusId', 'status', 'position', 'id');

            $listData = $this->getVideoTable()->getDatatables($columns, $data);

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
        $form = new VideoForm($statuses);

        $request = $this->getRequest();


        if ($request->isPost()) {
            $form->setInputFilter(new VideoFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $video = new Video();
                $video->exchangeArray($form->getData());

                $id = $this->getVideoTable()->save($video);

                $this->getBlockService()->saveBlocks($id, 'Video', $request->getPost()->toArray(), 'title');

                $this->flashMessenger()->addMessage('Video zostało dodane poprawnie.');

                return $this->redirect()->toRoute('video');
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
        $id = $this->params()->fromRoute('video_id');

        /* @var $video Video */
        $video = $this->getVideoTable()->getOneBy(array('id' => $id));

        if(!$video)  {
            return $this->redirect()->toRoute('video');
        }

        $blocks = $this->getBlockService()->getBlocks($video, 'Video');

        $statuses = $this->getStatusService()->findAsAssocArray();

        $form = new VideoForm($statuses);
        $form->bind($video);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new VideoFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $id = $this->getVideoTable()->save($video);

                $this->getBlockService()->saveBlocks($id, 'Video', $request->getPost()->toArray(), 'title');

                $this->flashMessenger()->addMessage('Video został edytowane poprawnie.');

                return $this->redirect()->toRoute('video');
            }
        }

        $viewParams = array();
        $viewParams['video'] = $video;
        $viewParams['form'] = $form;
        $viewParams['blocks'] = $blocks;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('video_id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('video');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');

                /* @var $video Video */
                $video = $this->getVideoTable()->getOneBy(array('id' => $id));

                if(!is_array($id)) {
                    $id = array($id);
                }

                $this->getVideoTable()->deleteVideo($id);
                $this->flashMessenger()->addMessage('Video zostało usunięte poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('video');
        }

        return array(
            'id'    => $id,
            'video' => $this->getVideoTable()->getOneBy(array('id' => $id))
        );
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('video_id');

        if (!$id) {
            return $this->redirect()->toRoute('video');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz') {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getVideoTable()->changeStatusVideo($id, $statusId);

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('video');
        }

        return array();
    }

    public function changePositionAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $position = $request->getPost('position');

            $this->getVideoTable()->changePosition($position);
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
        return $this->response;
    }

    /**
     * @return \CmsIr\Video\Model\VideoTable
     */
    public function getVideoTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Video\Model\VideoTable');
    }

    /**
     * @return \CmsIr\Video\Service\VideoService
     */
    public function getVideoService()
    {
        return $this->getServiceLocator()->get('CmsIr\Video\Service\VideoService');
    }

    /**
     * @return \CmsIr\System\Service\StatusService
     */
    public function getStatusService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\StatusService');
    }

    /**
     * @return \CmsIr\System\Service\BlockService
     */
    public function getBlockService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\BlockService');
    }
}