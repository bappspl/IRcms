<?php
namespace CmsIr\Newsletter\Controller;

use CmsIr\Newsletter\Model\Newsletter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use CmsIr\Newsletter\Model\SubscriberGroup;

use CmsIr\Newsletter\Form\SubscriberGroupForm;
use CmsIr\Newsletter\Form\SubscriberGroupFormFilter;

use Zend\Mail\Message;

class SubscriberController extends AbstractActionController
{
    protected $subscriberGroupTable;

    public function subscriberGroupAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array( 'name', 'slug');

            $listData = $this->getSubscriberGroupTable()->getDatatables($columns,$data);
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

    public function createSubscriberGroupAction ()
    {
        $form = new SubscriberGroupForm();

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new SubscriberGroupFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $subscriberGroup = new SubscriberGroup();

                $subscriberGroup->exchangeArray($form->getData());
                $this->getSubscriberGroupTable()->save($subscriberGroup);

                $this->flashMessenger()->addMessage('Grupa subskrybentów została dodana poprawnie.');

                return $this->redirect()->toRoute('subscriber-group');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editSubscriberGroupAction ()
    {
        $id = $this->params()->fromRoute('subscriber_group_id');

        $subscriberGroup = $this->getSubscriberGroupTable()->getOneBy(array('id' => $id));

        if(!$subscriberGroup) {
            return $this->redirect()->toRoute('subscriber-group');
        }

        $form = new SubscriberGroupForm();
        $form->bind($subscriberGroup);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new SubscriberGroupFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getSubscriberGroupTable()->save($subscriberGroup);
                $this->flashMessenger()->addMessage('Grupa subskrybentów została zedytowana poprawnie.');

                return $this->redirect()->toRoute('subscriber-group');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function previewSubscriberGroupAction ()
    {
        $id = $this->params()->fromRoute('subscriber_group_id');

        $subscriberGroup = $this->getSubscriberGroupTable()->getOneBy(array('id' => $id));

        if(!$subscriberGroup) {
            return $this->redirect()->toRoute('subscriber-group');
        }

        $form = new SubscriberGroupForm();
        $form->bind($subscriberGroup);

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteSubscriberGroupAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('subscriber_group_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('subscriber-group');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');
                $this->getSubscriberGroupTable()->deleteSubscriberGroup($id);
                $this->flashMessenger()->addMessage('Grupa subskrybentów została usunięta poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('subscriber-group');
        }

        return array();
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberGroupTable
     */
    public function getSubscriberGroupTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberGroupTable');
    }
}