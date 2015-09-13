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
        foreach($blocks as $block)
        {
            $fieldName = $block->getName();

            switch ($fieldName)
            {
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
