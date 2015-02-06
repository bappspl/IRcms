<?php
namespace CmsIr\System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;


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

        list($width, $height) = getimagesize($imgSrc);

        //$gd = new GD($sourcePath, array('resizeUp' => true, 'jpegQuality' => 92));

        $info = pathinfo($imgSrc);

        switch(strtolower(($info['extension'])))
        {
            case 'jpg':
                $myImage = imagecreatefromjpeg($imgSrc);
            break;
            case 'png':
                $myImage = imagecreatefrompng($imgSrc);
            break;
            case 'gif':
                $myImage = imagecreatefromgif($imgSrc);
            break;
            default:
                $myImage = imagecreatefromjpeg($imgSrc);
            break;

        }


        if ($width > $height) {
            $y = 0;
            $x = ($width - $height) / 2;
            $smallestSide = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $smallestSide = $width;
        }

        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
        imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $thumbWidth, $thumbHeight, $smallestSide, $smallestSide);

        header('Content-type: image/jpeg');
        imagejpeg($thumb);
        imagejpeg($thumb, $pathToThumbs . '/' .$fileName);

    }
}