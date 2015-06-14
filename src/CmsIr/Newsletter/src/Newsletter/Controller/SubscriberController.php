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
use CmsIr\Newsletter\Model\Subscriber;

use CmsIr\Newsletter\Form\SubscriberForm;
use CmsIr\Newsletter\Form\SubscriberFormFilter;
use CmsIr\Newsletter\Form\SubscriberGroupForm;
use CmsIr\Newsletter\Form\SubscriberGroupFormFilter;

use Zend\Mail\Message;

class SubscriberController extends AbstractActionController
{
    protected $subscriberGroupTable;

    public function subscriberListAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'email', 'groups', 'statusId', 'status', 'id');

            $listData = $this->getSubscriberTable()->getDatatables($columns,$data);
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

    public function createSubscriberAction()
    {
        $form = new SubscriberForm();

        $subscriberGroups = $this->getSubscriberGroupTable()->getAll();
        $tmpArrayGroups = array();
        foreach ($subscriberGroups as $keyGroup => $group) {
            $tmp = array(
                'value' => $group->getId(),
                'label' => $group->getName()
            );
            array_push($tmpArrayGroups, $tmp);
        }
        $form->get('groups')->setValueOptions($tmpArrayGroups);


        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new SubscriberFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $subscriber = new Subscriber();

                $confirmationCode = uniqid();
                $subscriber->setConfirmationCode($confirmationCode);

                $subscriber->exchangeArray($form->getData());
                $this->getSubscriberTable()->save($subscriber);

                $email = $subscriber->getEmail();
                $this->sendConfirmationEmail($email, $confirmationCode);

                $this->flashMessenger()->addMessage('Subskrybent został dodany poprawnie.');

                return $this->redirect()->toRoute('newsletter/subscriber-list');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function sendConfirmationEmail($email, $confirmationCode)
    {
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();
        $this->getRequest()->getServer();
        $message->addTo($email)
            ->addFrom('website@dnastudio.pl')
            ->setSubject('Prosimy o potwierdzenie subskrypcji!')
            ->setBody("W celu potwierdzenia subskrypcji kliknij w link => " .
                $this->getRequest()->getServer('HTTP_ORIGIN') .
                $this->url()->fromRoute('newsletter-confirmation', array('code' => $confirmationCode)));
        $transport->send($message);
    }

    public function editSubscriberAction()
    {
        $id = $this->params()->fromRoute('subscriber_id');
        $subscriber = $this->getSubscriberTable()->getOneBy(array('id' => $id));

        if(!$subscriber) {
            return $this->redirect()->toRoute('newsletter/subscriber-list');
        }

        $form = new SubscriberForm();
        $form->bind($subscriber);

        $subscriberGroup = $subscriber->getGroups();
        $subscriberArrayGroups = unserialize($subscriberGroup);

        $subscriberGroups = $this->getSubscriberGroupTable()->getAll();
        $tmpArrayGroups = array();
        foreach ($subscriberGroups as $keyGroup => $group) {

            if(in_array($group->getId(), $subscriberArrayGroups)){
                $tmp = array(
                    'value' => $group->getId(),
                    'label' => $group->getName(),
                    'selected' => true
                );
            } else {
                $tmp = array(
                    'value' => $group->getId(),
                    'label' => $group->getName()
                );
            }

            array_push($tmpArrayGroups, $tmp);
        }
        $form->get('groups')->setValueOptions($tmpArrayGroups);


        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new SubscriberFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getSubscriberTable()->save($subscriber);

                $this->flashMessenger()->addMessage('Subskrybent został zedytowany poprawnie.');

                return $this->redirect()->toRoute('newsletter/subscriber-list');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function previewSubscriberAction()
    {
        $id = $this->params()->fromRoute('subscriber_id');
        $subscriber = $this->getSubscriberTable()->getOneBy(array('id' => $id));

        if(!$subscriber) {
            return $this->redirect()->toRoute('newsletter/subscriber-list');
        }

        $form = new SubscriberForm();
        $form->bind($subscriber);

        $subscriberGroup = $subscriber->getGroups();
        $subscriberArrayGroups = unserialize($subscriberGroup);

        $subscriberGroups = $this->getSubscriberGroupTable()->getAll();
        $tmpArrayGroups = array();
        foreach ($subscriberGroups as $keyGroup => $group) {

            if(in_array($group->getId(), $subscriberArrayGroups)){
                $tmp = array(
                    'value' => $group->getId(),
                    'label' => $group->getName(),
                    'selected' => true
                );
            } else {
                $tmp = array(
                    'value' => $group->getId(),
                    'label' => $group->getName()
                );
            }

            array_push($tmpArrayGroups, $tmp);
        }
        $form->get('groups')->setValueOptions($tmpArrayGroups);

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteSubscriberAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('subscriber_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('newsletter/subscriber-list');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');
                if(!is_array($id))
                {
                    $id = array($id);
                }
                $this->getSubscriberTable()->deleteSubscriber($id);
                //$this->flashMessenger()->addMessage('Subskrybent został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('newsletter/subscriber-list');
        }

        return array();
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('subscriber_id');

        if (!$id) {
            return $this->redirect()->toRoute('newsletter/subscriber-list');
        }

        if ($request->isPost())
        {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz')
            {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getSubscriberTable()->changeStatusPost($id, $statusId);

                //$this->flashMessenger()->addMessage('Post został zedytowany poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('newsletter/subscriber-list');
        }

        return array();
    }

    public function subscriberGroupAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id','name', 'slug', 'id');

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

                return $this->redirect()->toRoute('newsletter/subscriber-group');
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
            return $this->redirect()->toRoute('newsletter/subscriber-group');
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

                return $this->redirect()->toRoute('newsletter/subscriber-group');
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
            return $this->redirect()->toRoute('newsletter/subscriber-group');
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
            return $this->redirect()->toRoute('newsletter/subscriber-group');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');
                if(!is_array($id))
                {
                    $id = array($id);
                }
                $connectedUsers = $this->getSubscriberTable()->getBySubscriberGroupId($id);

                if($connectedUsers)
                {
                    $this->flashMessenger()->addMessage('Nie można usunąć grupy, ponieważ są do niej przypisani subskrybenci!');
                    $jsonObject = Json::encode($params['status'] = 'error', true);
                    echo $jsonObject;
                    return $this->response;
                }

                $this->getSubscriberGroupTable()->deleteSubscriberGroup($id);
                //$this->flashMessenger()->addMessage('Grupa subskrybentów została usunięta poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('newsletter/subscriber-group');
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

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberTable
     */
    public function getSubscriberTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberTable');
    }
}