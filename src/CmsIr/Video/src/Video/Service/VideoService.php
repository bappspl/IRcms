<?php

namespace CmsIr\Video\Service;

use CmsIr\File\Model\File;
use CmsIr\System\Model\Block;
use CmsIr\Video\Model\Video;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class VideoService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findOneByLangIdWithBlocks($langId)
    {
        /* @var $video Video */
        $videos = $this->getVideoTable()->getAll();

        $video = $videos[array_rand($videos)];

        if(!$video) {
            return null;
        }
        $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Video', 'language_id' => $langId, 'entity_id' => $video->getId()));
        $video->setBlocks($blocks);

        /* @var $block Block */
        foreach($blocks as $block) {
            $fieldName = $block->getName();

            switch ($fieldName) {
                case 'title':
                    $video->setTitle($block->getValue());
                    break;
                case 'description':
                    $video->setDescription($block->getValue());
                    break;
            }
        }

        return $video;
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

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
    }

    /**
     * @return \CmsIr\Video\Model\VideoTable
     */
    public function getVideoTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Video\Model\VideoTable');
    }
}
