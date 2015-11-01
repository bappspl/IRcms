<?php

namespace CmsIr\Dictionary\Service;

use CmsIr\Category\Model\Category;
use CmsIr\Dictionary\Model\Dictionary;
use CmsIr\System\Model\Block;
use Product\Model\Product;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DictionaryService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function getBlocksToEntityByLangId($entity, $langId)
    {
        /* @var $entity Dictionary */
        $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Dictionary', 'entity_id' => $entity->getId(), 'language_id' => $langId));
        $entity->setBlocks($blocks);

        /* @var $block Block */
        foreach($blocks as $block) {
            $fieldName = $block->getName();

            switch ($fieldName) {
                case 'title':
                    $entity->setTitle($block->getValue());
                    break;
                case 'content':
                    $entity->setContent($block->getValue());
                    break;
            }
        }

        return $entity;
    }

    public function findDictionariesWithBlocksByNames($names, $langId)
    {
        $dictionaries = array();

        foreach($names as $name) {
            /* @var $dictionary Dictionary */
            $dictionary = $this->getDictionaryTable()->getOneBy(array('name' => $name));

            /* @var $entity Dictionary */
            $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Dictionary', 'entity_id' => $dictionary->getId(), 'language_id' => $langId));
            $dictionary->setBlocks($blocks);

            /* @var $block Block */
            foreach($blocks as $block) {
                $fieldName = $block->getName();

                switch ($fieldName) {
                    case 'title':
                        $dictionary->setTitle($block->getValue());
                        break;
                    case 'content':
                        $dictionary->setContent($block->getValue());
                        break;
                }
            }

            $dictionaries[$dictionary->getName()] = $dictionary;
        }

        return $dictionaries;
    }

    public function findDictionariesAsAssocArrayByCategory($category)
    {
        $dictionariesArray = array();

        $dictionaries = $this->getDictionaryTable()->getBy(array('category' => $category));

        /* @var $dictionary Dictionary */
        foreach($dictionaries as $dictionary) {
            $dictionariesArray[$dictionary->getId()] = $dictionary->getName();
        }

        return $dictionariesArray;
    }

    public function findDictionaryById($id, $langId)
    {
        if($id == null) {
            return null;
        }

        $dictionary = $this->getDictionaryTable()->getOneBy(array('id' => $id));

        /* @var $dictionary Dictionary */
        $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'Dictionary', 'entity_id' => $dictionary->getId(), 'language_id' => $langId));

        /* @var $block Block */
        foreach($blocks as $block) {
            $fieldName = $block->getName();

            switch ($fieldName) {
                case 'title':
                    $dictionary->setTitle($block->getValue());
                    break;
                case 'content':
                    $dictionary->setContent($block->getValue());
                    break;
            }
        }

        $dictionary->setBlocks($blocks);

        return $dictionary;
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
     * @return \CmsIr\Dictionary\Model\DictionaryTable
     */
    public function getDictionaryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Dictionary\Model\DictionaryTable');
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
