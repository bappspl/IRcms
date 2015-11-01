<?php

namespace CmsIr\File\Service;

use CmsIr\File\Model\File;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FileService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findAllByCategoryAndWebsiteId($category, $websiteId)
    {
        $files = $this->getFileTable()->getBy(array('category' => $category, 'website_id' => $websiteId));

        return $files;
    }

    public function findLastPictures($count, $websiteId = null)
    {
        $filesArray = array();

        $galleries = $this->getFileTable()->getBy(array('category' => 'gallery', 'website_id' => $websiteId), 'id DESC');

        $counter = 0;

        /* @var $gallery File */
        foreach ($galleries as $gallery) {
            $files = $gallery->getFilename();

            if(!empty($files)) {
                $files = unserialize($files);

                foreach($files as $file) {
                    array_push($filesArray, $file);
                    $counter++;

                    if($counter >= $count) {
                        break;
                    }
                }
            }
        }

        return $filesArray;
    }

    public function getMimeContentType($filename)
    {
        if(!function_exists('finfo_open')) {
            $mimeType = $this->getMimeTypeFromFilename($filename);
        } else {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }

        return $mimeType;
    }

    public function getMimeTypeFromFilename($filename)
    {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));

        if (array_key_exists($ext, $mime_types)) {
            $mimeType = $mime_types[$ext];
        } else {
            $mimeType = 'application/octet-stream';
        }

        return $mimeType;
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
    }

    /**
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}
