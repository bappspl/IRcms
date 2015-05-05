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
        foreach ($galleries as $gallery)
        {
            $files = $gallery->getFilename();
            if(!empty($files))
            {
                $files = unserialize($files);

                foreach($files as $file)
                {
                    array_push($filesArray, $file);
                    $counter++;

                    if($counter >= $count)
                    {
                        break;
                    }
                }
            }
        }

        return $filesArray;
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
