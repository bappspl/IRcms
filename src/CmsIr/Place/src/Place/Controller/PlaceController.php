<?php

namespace CmsIr\Place\Controller;

use CmsIr\Place\Form\PlaceForm;
use CmsIr\Place\Form\PlaceFormFilter;
use CmsIr\Place\Model\Place;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;

class PlaceController extends AbstractActionController {

    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'latitude', 'longitude', 'id');

            $listData = $this->getPlaceTable()->getDatatables($columns, $data);
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
        $form = new PlaceForm();

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new PlaceFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $place = new Place();
                $place->exchangeArray($form->getData());
                $region = $place->getRegion();
                if($region === 'mazowieckie') {
                    $place->setRegion('Województwo mazowieckie');
                }

                $this->getPlaceTable()->save($place);

                $this->flashMessenger()->addMessage('Miejsce zostało utworzone poprawnie.');
                return $this->redirect()->toRoute('place');
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
        $id = $this->params()->fromRoute('id');
        /**
         * @var $post Place
         */
        $place = $this->getPlaceTable()->getOneBy(array('id' => $id));
        if(!$place) {
            return $this->redirect()->toRoute('place');
        }

        $form = new PlaceForm();
        $form->bind($place);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new PlaceFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $region = $place->getRegion();
                if($region === 'mazowieckie') {
                    $place->setRegion('Województwo mazowieckie');
                }

                $this->getPlaceTable()->save($place);

                $this->flashMessenger()->addMessage('Miejsce zostało zedytowane poprawnie.');
                return $this->redirect()->toRoute('place');
            }
        }
        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function previewAction()
    {
        $id = $this->params()->fromRoute('id');
        /**
         * @var $post Place
         */
        $place = $this->getPlaceTable()->getOneBy(array('id' => $id));
        if(!$place) {
            return $this->redirect()->toRoute('place');
        }

        $form = new PlaceForm();
        $form->bind($place);

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('palce');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');

                if(!is_array($id)) {
                    $id = array($id);
                }

                $this->getPlaceTable()->deletePlace($id);

                //$this->flashMessenger()->addMessage('Post został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('place');
        }

        return array();
    }


    /**
     * @return \CmsIr\Place\Model\PlaceTable
     */
    public function getPlaceTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Place\Model\PlaceTable');
    }

}