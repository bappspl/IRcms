<?php
namespace CmsIr\System\Controller;

use CmsIr\File\Model\Gallery;
use CmsIr\System\Model\Settings;
use CmsIr\Video\Model\Video;
use PHPThumb\GD;
use CmsIr\System\Form\MailConfigForm;
use CmsIr\System\Form\MailConfigFormFilter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;


class SystemController extends AbstractActionController
{
    protected $pathToEditorFiles = 'public/files/editor/';


    public function mailConfigAction()
    {
        $id = 1;

        $config = $this->getMailConfigTable()->getOneById($id);

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

//                $settings = $request->getPost()->settings;
//                $values = explode('-', $settings);
//
//                $settingsObject = new Settings();
//                $settingsObject->setId(1);
//                $settingsObject->setEntityId($values[1]);
//                $settingsObject->setEntityType($values[0]);
//                $settingsObject->setName('opcja');
//
//                $this->getSettingsTable()->save($settingsObject);

                $this->flashMessenger()->addMessage('Ustawienia zostały edytowane poprawnie.', FlashMessenger::NAMESPACE_SUCCESS);

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
            } else  {
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

        if(file_exists($pathToThumbs.'/'.$fileName)) {
            $gd = new GD($pathToThumbs.'/'.$fileName);
            $gd->show();
        } else {
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

    /**
     * @return \CmsIr\System\Model\MailConfigTable
     */
    public function getMailConfigTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\MailConfigTable');
    }

    /**
     * @return \CmsIr\File\Model\GalleryTable
     */
    public function getGalleryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\GalleryTable');
    }

    /**
     * @return \CmsIr\Video\Model\VideoTable
     */
    public function getVideoTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Video\Model\VideoTable');
    }

    /**
     * @return \CmsIr\System\Model\SettingsTable
     */
    public function getSettingsTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\SettingsTable');
    }
}