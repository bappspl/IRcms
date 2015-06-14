<?php
namespace CmsIr\Newsletter\Controller;

use CmsIr\Newsletter\Model\Newsletter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use CmsIr\Newsletter\Form\NewsletterForm;
use CmsIr\Newsletter\Form\NewsletterSettingsForm;
use CmsIr\Newsletter\Form\NewsletterFormFilter;
use CmsIr\Newsletter\Form\NewsletterSettingsFormFilter;

use Zend\Mail\Message;

class NewsletterController extends AbstractActionController
{
    protected $newsletterTable;
    protected $uploadDir = 'public/files/newsletter/';
    protected $appName = 'Cms-ir';

    public function newsletterAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'subject', 'groups', 'statusId', 'status', 'id');

            $listData = $this->getNewsletterTable()->getDatatables($columns,$data);
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

    public function sendNewsletterAction ()
    {
        $id = $this->params()->fromRoute('newsletter_id');
        $newsletter = $this->getNewsletterTable()->getOneBy(array('id' => $id));
        $newsletterSettings = $this->getNewsletterSettingsTable()->getOneBy(array('id' => 1));

        $newsletterContent = $newsletter->getText() . '<br>' . $newsletterSettings->getFooter();

        if(!$newsletter) {
            return $this->redirect()->toRoute('newsletter');
        }

        $groups = unserialize($newsletter->getGroups());

        $confirmedStatus = $this->getStatusTable()->getOneBy(array('slug' => 'confirmed'));
        $confirmedStatusId = $confirmedStatus->getId();

        $subscribers = $this->getSubscriberTable()->getBy(array('status_id' => $confirmedStatusId));

        $subscriberEmails = array();
        foreach($subscribers as $subscriber)
        {
            $subscriberGroups = unserialize($subscriber->getGroups());
            foreach($subscriberGroups as $group)
            {
                if(in_array($group, $groups))
                {
                    $subscriberEmails[$subscriber->getEmail()] = $subscriber->getEmail();
                }
            }
        }
        $this->sendEmails($subscriberEmails, $newsletter->getSubject(), $newsletterContent);

        $sendStatus = $this->getStatusTable()->getOneBy(array('slug' => 'send'));
        $newsletter->setStatusId($sendStatus->getId());
        $newsletter->setGroups($groups);
        $this->getNewsletterTable()->save($newsletter);

        $this->flashMessenger()->addMessage('Wiadomości zostały wysłane.');

        return $this->redirect()->toRoute('newsletter');
    }

    public function sendEmails($emails, $subject, $content)
    {
        $transport = $this->getServiceLocator()->get('mail.transport');

        $html = new MimePart($content);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($html));

        foreach($emails as $email)
        {
            $message = new Message();
            $this->getRequest()->getServer();
            $message->addTo($email)
                ->addFrom('website@dnastudio.pl')
                ->setEncoding('UTF-8')
                ->setSubject($subject)
                ->setBody($body);
            $transport->send($message);
        }
    }

    public function createNewsletterAction()
    {
        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $form = $formManager->create('newsletterForm');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new NewsletterFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $newsletter = new Newsletter();

                $newsletter->exchangeArray($form->getData());
                $this->getNewsletterTable()->save($newsletter);

                $this->flashMessenger()->addMessage('Wiadomość została dodana poprawnie.');

                return $this->redirect()->toRoute('newsletter');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editNewsletterAction()
    {
        $id = $this->params()->fromRoute('newsletter_id');
        $newsletter = $this->getNewsletterTable()->getOneBy(array('id' => $id));

        if(!$newsletter) {
            return $this->redirect()->toRoute('newsletter');
        }

        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $form = $formManager->create('newsletterForm');

        $newsletterGroups = $newsletter->getGroups();
        $newsletterArrayGroups = unserialize($newsletterGroups);

        $newsletter->setGroups($newsletterArrayGroups);
        $form->bind($newsletter);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new NewsletterFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getNewsletterTable()->save($newsletter);
                $this->flashMessenger()->addMessage('Wiadomość została zedytowana poprawnie.');

                return $this->redirect()->toRoute('newsletter');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function previewNewsletterAction()
    {
        $id = $this->params()->fromRoute('newsletter_id');
        $newsletter = $this->getNewsletterTable()->getOneBy(array('id' => $id));

        if(!$newsletter) {
            return $this->redirect()->toRoute('newsletter');
        }

        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $form = $formManager->create('newsletterForm');

        $newsletterGroups = $newsletter->getGroups();
        $newsletterArrayGroups = unserialize($newsletterGroups);

        $newsletter->setGroups($newsletterArrayGroups);
        $form->bind($newsletter);

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteNewsletterAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('newsletter_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('newsletter');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = $request->getPost('id');
                if(!is_array($id))
                {
                    $id = array($id);
                }
                $this->getNewsletterTable()->deleteNewsletter($id);
//                $this->flashMessenger()->addMessage('Wiadomość została usunięta poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('newsletter');
        }

        return array();
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('newsletter_id');

        if (!$id) {
            return $this->redirect()->toRoute('newsletter');
        }

        if ($request->isPost())
        {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz')
            {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getNewsletterTable()->changeStatusPost($id, $statusId);

                //$this->flashMessenger()->addMessage('Post został zedytowany poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('newsletter');
        }

        return array();
    }

    public function newsletterSettingsAction()
    {
        $settings = $this->getNewsletterSettingsTable()->getOneBy(array('id' => 1));

        if(!$settings) {
            return $this->redirect()->toRoute('newsletter');
        }
        $form = new NewsletterSettingsForm();
        $form->bind($settings);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new NewsletterSettingsFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getNewsletterSettingsTable()->save($settings);
                $this->flashMessenger()->addMessage('Ustawienia zostały zedytowane poprawnie.');

                return $this->redirect()->toRoute('newsletter');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    /**
     * @return \CmsIr\Newsletter\Model\NewsletterTable
     */
    public function getNewsletterTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\NewsletterTable');
    }

    /**
     * @return \CmsIr\Newsletter\Model\NewsletterSettingsTable
     */
    public function getNewsletterSettingsTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\NewsletterSettingsTable');
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberGroupTable
     */
    public function getSubscriberGroupTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberGroupTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberTable
     */
    public function getSubscriberTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberTable');
    }
}