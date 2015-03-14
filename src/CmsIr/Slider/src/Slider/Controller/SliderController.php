<?php
namespace CmsIr\Slider\Controller;

use CmsIr\Slider\Form\SliderForm;
use CmsIr\Slider\Form\SliderFormFilter;
use CmsIr\Slider\Form\SliderItemFilter;
use CmsIr\Slider\Form\SliderItemForm;
use CmsIr\Slider\Form\SliderItemFormFilter;
use CmsIr\Slider\Model\Slider;
use CmsIr\Slider\Model\SliderItem;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class SliderController extends AbstractActionController
{
    protected $uploadDir = 'public/files/slider/';

    public function listAction()
    {
        $request = $this->getRequest();
        $currentWebsiteId = $_COOKIE['website_id'];

        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('name', 'slug');

            $listData = $this->getSliderTable()->getDatatables($columns,$data, $currentWebsiteId);

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
        $form = new SliderForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new SliderFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $slider = new Slider();

                $slider->exchangeArray($form->getData());
                $this->getSliderTable()->save($slider);

                $this->flashMessenger()->addMessage('Slider został dodany poprawnie.');

                return $this->redirect()->toRoute('slider');
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
        $id = $this->params()->fromRoute('slider_id');
        $currentWebsiteId = $_COOKIE['website_id'];
        /**
         * @var $slider Slider
         */
        $slider = $this->getSliderTable()->getOneBy(array('id' => $id));

        if(!$slider) {
            return $this->redirect()->toRoute('slider');
        }

        $form = new SliderForm();
        $form->bind($slider);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new SliderFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $slider->setWebsiteId($currentWebsiteId);
                $this->getSliderTable()->save($slider);

                $this->flashMessenger()->addMessage('Slider został edytowany poprawnie.');

                return $this->redirect()->toRoute('slider');
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

    public function itemsAction()
    {
        $sliderId = $this->params()->fromRoute('slider_id');
        $items = $this->getSliderItemTable()->getBy(array('slider_id' => $sliderId));

        $viewParams = array();
        $viewParams['sliderId'] = $sliderId;
        $viewParams['items'] = $items;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function createItemAction()
    {
        $sliderId = $this->params()->fromRoute('slider_id');

        if(!$sliderId) {
            return $this->redirect()->toRoute('slider');
        }

        $form  = new SliderItemForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new SliderItemFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $sliderItem = new SliderItem();

                $sliderItem->exchangeArray($form->getData());

                $sliderItem->setSliderId($sliderId);
                $this->getSliderItemTable()->save($sliderItem);

                $this->flashMessenger()->addMessage('Slide został dodany poprawnie.');

                return $this->redirect()->toRoute('slider/items', array('slider_id' => $sliderId));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editItemAction()
    {
        $sliderId = $this->params()->fromRoute('slider_id');
        $itemId = $this->params()->fromRoute('item_id');

        if(!$sliderId && !$itemId) {
            return $this->redirect()->toRoute('slider');
        }

        $sliderItem = $this->getSliderItemTable()->getOneBy(array('id' => $itemId));

        $form = new SliderItemForm();
        $form->bind($sliderItem);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new SliderItemFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getSliderItemTable()->save($sliderItem);

                $this->flashMessenger()->addMessage('Slide został edytowany poprawnie.');

                return $this->redirect()->toRoute('slider/items', array('slider_id' => $sliderId));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function orderAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost('data');
            $this->getSliderItemTable()->saveItems($data);

            $jsonObject = Json::encode($params['status'] = 'success', true);
            echo $jsonObject;
            return $this->response;
        }
        return $this->response;
    }

    public function deleteItemAction()
    {
        $request = $this->getRequest();
        $sliderId = (int) $this->params()->fromRoute('slider_id', 0);
        $itemId = (int) $this->params()->fromRoute('item_id', 0);
        if (!$sliderId && !$itemId) {
            return $this->redirect()->toRoute('slider');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $this->getSliderItemTable()->deleteSliderItem($itemId);
                $this->flashMessenger()->addMessage('Element slidera został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $itemId, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('slider');
        }

        return array(
            'id'    => $itemId,
            'item' => $this->getSliderItemTable()->getOneBy(array('id' => $itemId))
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

    /**
     * @return \CmsIr\Slider\Model\SliderItemTable
     */
    public function getSliderItemTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Model\SliderItemTable');
    }
}