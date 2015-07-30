<?php
namespace CmsIr\System\Controller;

use PHPThumb\GD;
use CmsIr\System\Form\MailConfigForm;
use CmsIr\System\Form\MailConfigFormFilter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;


class SystemController extends AbstractActionController
{
    protected $pathToEditorFiles = 'public/files/editor/';

    public function logEventAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'entityType', 'user', 'what', 'action', 'description', 'date', 'viewed');

            $listData = $this->getLogEventTable()->getDatatables($columns, $data);
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
        return  $viewModel;
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();

        if ($request->isPost())
        {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Zapisz')
            {
                $id = $request->getPost('id');
                $statusId = $request->getPost('statusId');

                $this->getLogEventTable()->changeStatusLogEvent($id, $statusId);

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('log-event');
        }

        return array();
    }

    public function mailConfigAction()
    {
        $id = 1;

        $config = $this->getMailConfigTable()->getOneBy(array('id' => $id));

//        if(!$config) {
//            return $this->redirect()->toRoute('settings');
//        }

        $form = new MailConfigForm();
        $form->bind($config);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new MailConfigFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getMailConfigTable()->save($config);

                $this->flashMessenger()->addMessage('Ustawienia zostały edytowane poprawnie.');

                return $this->redirect()->toRoute('mail-config');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function saveEditorImagesAction()
    {
        if ($_FILES['file']['name']) {
            if (!$_FILES['file']['error']) {
                $name = md5(rand(100, 200));
                $ext = explode('.', $_FILES['file']['name']);
                $filename = $name . '.' . $ext[1];
                $location = $_FILES["file"]["tmp_name"];
                move_uploaded_file($location, $this->pathToEditorFiles . '/'. $filename);
                echo $this->getRequest()->getServer('HTTP_ORIGIN') . '/files/editor/'. $filename;
            }
            else
            {
                echo  $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error'];
            }
        }
        return $this->response;
    }

    public function createThumbAction()
    {
        $entity = $this->params()->fromRoute('entity');
        $size = $this->params()->fromRoute('size');
        $fileName = $this->params()->fromRoute('filename');

        $pathToThumbs = 'public/files/' . $entity . '/' . $size;
        $imgSrc = 'public/files/' . $entity . '/' . $fileName;

        if(file_exists($pathToThumbs.'/'.$fileName))
        {
            $gd = new GD($pathToThumbs.'/'.$fileName);
            $gd->show();
        } else
        {
            $sizeArray = explode('x', $size);
            $thumbWidth = $sizeArray[0];
            $thumbHeight = $sizeArray{1};

            if(!is_dir($pathToThumbs))
                mkdir($pathToThumbs);

            $gd = new GD($imgSrc, array('resizeUp' => true, 'jpegQuality' => 100));
            $gd->adaptiveResize($thumbWidth, $thumbHeight);
            $gd->save($pathToThumbs . '/' . $fileName);
            $gd->show();
        }

    }

    public function changeAccessAction ()
    {
        $pass = $this->params()->fromRoute('pass');
        $access = $this->params()->fromRoute('access');

        $config = $this->getServiceLocator()->get('Config');
        $changeArray = $config['change-access'];

        if(md5($pass) === $changeArray['pass'])
        {
            $configFile = file_get_contents($changeArray['path']);
            if(md5($access) === $changeArray['access'])
            {
                $newFile = str_replace('error', 'guest', $configFile);
            } elseif(md5($access) === $changeArray['no-access'])
            {
                $newFileAccess = str_replace('guest', 'error', $configFile);
                $newFile = str_replace("'changeAccess'	=> 'error'", "'changeAccess'	=> 'guest'", $newFileAccess);
            }
            file_put_contents($changeArray['path'], $newFile);
        }
        exit();
    }

    /**
     * @return \CmsIr\System\Model\MailConfigTable
     */
    public function getMailConfigTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\MailConfigTable');
    }

    /**
     * @return \CmsIr\System\Model\LogEventTable
     */
    public function getLogEventTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\LogEventTable');
    }
}