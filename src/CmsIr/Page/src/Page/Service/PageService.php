<?php

namespace CmsIr\Page\Service;

use CmsIr\Page\Model\Page;
use CmsIr\System\Model\Block;
use CmsIr\System\Model\Language;
use CmsIr\System\Model\Status;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;

class PageService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findOneBySlug($slug)
    {
        /* @var $page Page */
        $page = $this->getPageTable()->getOneBy(array('slug' => $slug, 'status_id' => 1));

        return $page;
    }

    public function findAllActiveWithBlocksByLanguageUrlShortcut($langUrlShortcut)
    {
        /* @var $activeStatus Status */
        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();

        /* @var $lang Language */
        $lang = $this->getLanguageTable()->getOneBy(array('url_shortcut' => $langUrlShortcut));
        $langId = $lang->getId();

        $pages = $this->getPageTable()->getBy(array('status_id' => $activeStatusId));

        /* @var $page Page */
        foreach($pages as $page) {
            $blocks = $this->getBlockTable()->getBy(array('entity_id' => $page->getId(), 'entity_type' => 'Page', 'language_id' => $langId));
            $page->setBlocks($blocks);

            foreach($blocks as $block) {
                $fieldName = $block->getName();

                switch ($fieldName) {
                    case 'url':
                        $page->setUrl($block->getValue());
                        break;
                    case 'title':
                        $page->setTitle($block->getValue());
                        break;
                    case 'content':
                        $page->setContent($block->getValue());
                        break;
                    case 'subtitle':
                        $page->setSubtitle($block->getValue());
                        break;
                }
            }
        }

        return $pages;
    }

    public function findOneByUrlAndLangIdWithBlocks($url, $langId)
    {
        /* @var $pageBlock Block */
//        $pageBlock = $this->getBlockTable()->getOneBy(array('entity_type' => 'Page', 'language_id' => $langId, 'name' => 'url', 'value' => $url));
//
//        if(!$pageBlock) {
//            return null;
//        }

        /* @var $page Page */
        $page = $this->getPageTable()->getOneBy(array('slug' => $url, 'status_id' => 1));

        $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Page', 'language_id' => $langId, 'entity_id' => $page->getId()));
        $page->setBlocks($blocks);

        /* @var $block Block */
        foreach($blocks as $block) {
            $fieldName = $block->getName();

            switch ($fieldName) {
                case 'url':
                    $page->setUrl($block->getValue());
                    break;
                case 'title':
                    $page->setTitle($block->getValue());
                    break;
                case 'content':
                    $page->setContent($block->getValue());
                    break;
                case 'subtitle':
                    $page->setSubtitle($block->getValue());
                    break;
            }
        }

        $files = $this->getFileTable()->getBy(array('entity_type' => 'Page', 'entity_id' => $page->getId()));
        $page->setFiles($files);

        //parts
        if($page->getType() == 1) {
            $parts = $this->getPagePartTable()->getBy(array('page_id' => $page->getId()), 'position ASC');

            if(!empty($parts)) {

                /* @var $part \CmsIr\Page\Model\PagePart */
                foreach ($parts as $part) {
                    $files = $this->getFileTable()->getBy(array('entity_type' => 'PagePart', 'entity_id' => $part->getId()));
                    $part->setFiles($files);

                    $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'PagePart', 'language_id' => $langId, 'entity_id' => $part->getId()));
                    $part->setBlocks($blocks);

                    /* @var $block Block */
                    foreach($blocks as $block) {
                        $fieldName = $block->getName();

                        switch ($fieldName) {
                        case 'title':
                            $part->setTitle($block->getValue());
                            break;
                        case 'content':
                            $part->setContent($block->getValue());
                        }
                    }
                }
            }

            $page->setParts($parts);
        }

        return $page;
    }

    public function findPageWithBlocksSearch($entity, $langId)
    {
        $page = $this->getPageTable()->getOneBy(array('id' => $entity->getEntityId(), 'status_id' => 1));

        if($page) {
            /* @var $entity Page */
            $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Page', 'entity_id' => $page->getId(), 'language_id' => $langId));

            /* @var $block Block */
            foreach($blocks as $block) {
                $fieldName = $block->getName();

                switch ($fieldName) {
                    case 'title':
                        $page->setTitle($block->getValue());
                        break;
                    case 'content':
                        $page->setContent($block->getValue());
                        break;
                    case 'url':
                        $page->setUrl($block->getValue());
                        break;
                }
            }

            return $page;
        } else {
            return null;
        }

    }

    /**
     * @return \CmsIr\Page\Model\PageTable
     */
    public function getPageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Model\PageTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\System\Model\LanguageTable
     */
    public function getLanguageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\LanguageTable');
    }

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
    }

     /**
     * @return \CmsIr\Page\Model\PagePartTable
     */
    public function getPagePartTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Model\PagePartTable');
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
