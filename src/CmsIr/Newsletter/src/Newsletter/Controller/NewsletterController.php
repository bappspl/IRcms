<?php
namespace CmsIr\Newsletter\Controller;

use CmsIr\Newsletter\Model\Newsletter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

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


    /**
     * @return \CmsIr\Newsletter\Model\NewsletterTable
     */
    public function getNewsletterTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\NewsletterTable');
    }
}