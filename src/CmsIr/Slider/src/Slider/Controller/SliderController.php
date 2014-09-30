<?php
namespace CmsIr\Slider\Controller;

use CmsIr\Slider\Form\SliderForm;
use CmsIr\Slider\Form\SliderFormFilter;
use CmsIr\Slider\Model\Slider;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class SliderController extends AbstractActionController
{
    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array( 'name', 'slug', 'status');

            $listData = $this->getSliderTable()->findBy($columns,$data);

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
        $test = new Slider();

        $form = new SliderForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new SliderFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();


                $this->flashMessenger()->addMessage('Użytkownik został dodany poprawnie.');

                return $this->redirect()->toRoute('users-list');
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
        $viewParams = array();
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('slider_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('slider');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');
                $this->getSliderTable()->deleteSlider($id);
                $this->flashMessenger()->addMessage('Slider został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('slider');
        }

        return array(
            'id'    => $id,
            'slider' => $this->getSliderTable()->getSlider($id)
        );
    }

    /**
     * @return \CmsIr\Slider\Model\SliderTable
     */
    public function getSliderTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Model\SliderTable');
    }

    /**
     * @return \CmsIr\Slider\Service\SliderService
     */
    public function getSliderService()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Service\SliderService');
    }
}