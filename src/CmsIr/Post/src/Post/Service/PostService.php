<?php

namespace CmsIr\Post\Service;

use CmsIr\Post\Model\Post;
use CmsIr\System\Model\Block;
use CmsIr\Users\Model\Users;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;

class PostService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findLastPostsByLangIdWithBlocks($langId, $category, $dateFormat, $counter = null)
    {
        $posts = $this->getPostTable()->getBy(array('status_id' => 1, 'category' => $category), 'id DESC', $counter);

        /* @var $post Post */
        foreach ($posts as $post)
        {
            $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Post', 'language_id' => $langId, 'entity_id' => $post->getId()));
            $post->setBlocks($blocks);

            /* @var $block Block */
            foreach($blocks as $block)
            {
                $fieldName = $block->getName();

                switch ($fieldName)
                {
                    case 'url':
                        $post->setUrl($block->getValue());
                        break;
                    case 'title':
                        $post->setTitle($block->getValue());
                        break;
                    case 'content':
                        $post->setContent($block->getValue());
                        break;
                    case 'client':
                        $post->setClient($block->getValue());
                        break;
                }
            }

            if($post->getAuthorId())
            {
                /* @var $user Users */
                $user = $this->getUsersTable()->getOneBy(array('id' => $post->getAuthorId()));
                $post->setAuthor($user->getName() . ' ' . $user->getSurname());
            }

            $date = $post->getDate();
            $date = new \DateTime($date);
            $post->setDate($date->format($dateFormat));

            $files = $this->getFileTable()->getBy(array('entity_type' => 'Post', 'entity_id' => $post->getId()));
            $post->setFiles($files);
        }

        return $posts;
    }

    public function findOneByUrlAndLangIdWithBlocks($url, $langId, $category)
    {
        /* @var $postBlock Block */
        $postBlock = $this->getBlockTable()->getOneBy(array('entity_type' => 'Post', 'language_id' => $langId, 'name' => 'url', 'value' => $url));

        if(!$postBlock)
        {
            return null;
        }

        /* @var $post Post */
        $post = $this->getPostTable()->getOneBy(array('id' => $postBlock->getEntityId(), 'status_id' => 1, 'category' => $category));


        $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Post', 'language_id' => $langId, 'entity_id' => $post->getId()));
        $post->setBlocks($blocks);

        /* @var $block Block */
        foreach($blocks as $block)
        {
            $fieldName = $block->getName();

            switch ($fieldName)
            {
                case 'url':
                    $post->setUrl($block->getValue());
                    break;
                case 'title':
                    $post->setTitle($block->getValue());
                    break;
                case 'content':
                    $post->setContent($block->getValue());
                    break;
            }
        }

        /* @var $user Users */
        $user = $this->getUsersTable()->getOneBy(array('id' => $post->getAuthorId()));
        $post->setAuthor($user->getName() . ' ' . $user->getSurname());

        $date = $post->getDate();
        $date = new \DateTime($date);
        $post->setDate($date->format('j M'));

        $files = $this->getFileTable()->getBy(array('entity_type' => 'Post', 'entity_id' => $post->getId()));
        $post->setFiles($files);

        return $post;
    }

    /**
     * @return \CmsIr\Slider\Model\SliderTable
     */
    public function getSliderTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Model\SliderTable');
    }

    /**
     * @return \CmsIr\Slider\Model\SliderItemTable
     */
    public function getSliderItemTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Model\SliderItemTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\Post\Model\PostTable
     */
    public function getPostTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Model\PostTable');
    }

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
    }

    /**
     * @return \CmsIr\Users\Model\UsersTable
     */
    public function getUsersTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Users\Model\UsersTable');
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
