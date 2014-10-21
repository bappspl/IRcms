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
use CmsIr\Newsletter\Form\NewsletterFormFilter;

use Zend\Mail\Message;

class NewsletterController extends AbstractActionController
{
    protected $newsletterTable;
    protected $uploadDir = 'public/files/newsletter/';
    protected $appName = 'Cms-ir';

    public function newsletterAction()
    {
        //var_dump(serialize(array('testowa', 'testowa2'))); die;
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array( 'subject');

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

    public function createNewsletterAction()
    {
        $form = new NewsletterForm();

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

        $form = new NewsletterForm();
        $form->bind($newsletter);

        $newsletterGroups = $newsletter->getGroups();
        $newsletterArrayGroups = unserialize($newsletterGroups);

        $subscriberGroups = $this->getSubscriberGroupTable()->getAll();
        $tmpArrayGroups = array();
        foreach ($subscriberGroups as $keyGroup => $group) {

            if(in_array($group->getId(), $newsletterArrayGroups)){
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
                $id = (int) $request->getPost('id');
                $this->getNewsletterTable()->deleteNewsletter($id);
                $this->flashMessenger()->addMessage('Wiadomość została usunięta poprawnie.');
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

    /**
     * @return \CmsIr\Newsletter\Model\NewsletterTable
     */
    public function getNewsletterTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\NewsletterTable');
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberGroupTable
     */
    public function getSubscriberGroupTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberGroupTable');
    }
}