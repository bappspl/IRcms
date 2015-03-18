<?php
namespace CmsIr\System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;
use PHPThumb\GD;


class SystemController extends AbstractActionController
{
    protected $pathToEditorFiles = 'public/files/editor/';

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