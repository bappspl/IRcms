<?php
namespace CmsIr\Meta\Controller;

use CmsIr\Banner\Form\BannerForm;
use CmsIr\Banner\Form\BannerFormFilter;
use CmsIr\Banner\Model\Banner;
use CmsIr\System\Util\Inflector;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class MetaController extends AbstractActionController
{

    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost())
        {

            $data = $this->getRequest()->getPost();
            $columns = array('name');

            $listData = $this->getMetaTable()->getDatatables($columns, $data);

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

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('meta_id', 0);

        if (!$id)
        {
            return $this->redirect()->toRoute('meta');
        }

        if ($request->isPost())
        {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak')
            {
                $id = (int) $request->getPost('id');

                $this->getMetaTable()->deleteBanner($id);
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
            'meta'  => $this->getMetaTable()->getOneBy(array('id' => $id))
        );
    }

    /**
     * @return \CmsIr\Meta\Model\MetaTable
     */
    public function getMetaTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Meta\Model\MetaTable');
    }

    /**
     * @return \CmsIr\Meta\Service\MetaService
     */
    public function getMetaService()
    {
        return $this->getServiceLocator()->get('CmsIr\Meta\Service\MetaService');
    }
}