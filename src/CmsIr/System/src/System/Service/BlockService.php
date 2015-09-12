<?php

namespace CmsIr\System\Service;

use CmsIr\System\Model\Block;
use CmsIr\System\Model\Language;
use CmsIr\System\Util\Inflector;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlockService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function saveBlocks($entity_id, $entity_type, $postArrayValues, $notEmpty = null)
    {
        $langArray = array();
        $languages = $this->getLanguageTable()->getAll();

        /* @var $lang Language */
        foreach ($languages as $lang)
        {
            $langArray[$lang->getUrlShortcut()] = $lang->getId();

            if($this->preg_array_key_exists('/' . $lang->getUrlShortcut() . '-url/', $postArrayValues) && strlen($postArrayValues[$lang->getUrlShortcut() . '-url']) == 0)
            {
                $postArrayValues[$lang->getUrlShortcut() . '-url'] = Inflector::slugify($postArrayValues['name']);
            }

            if($notEmpty && isset($postArrayValues[$lang->getUrlShortcut() . '-' . $notEmpty]))
            {
                if(strlen($postArrayValues[$lang->getUrlShortcut() . '-' . $notEmpty]) == 0)
                {
                    $postArrayValues[$lang->getUrlShortcut() . '-' . $notEmpty] = Inflector::slugify($postArrayValues['name']);
                }
            }
        }

        foreach($postArrayValues as $k =>$value)
        {
            if(strpos($k, '-') !== false && strlen($value) > 0)
            {
                $split = explode('-', $k);
                $lang = $split[0];
                $name = $split[1];

                $block = $this->getBlockTable()->getOneBy(array(
                    'entity_id' => $entity_id,
                    'entity_type' => $entity_type,
                    'language_id' => $langArray[$lang],
                    'name' => $name
                ));

                if(!$block)
                {
                    $block = new Block();
                    $block->setEntityType($entity_type);
                    $block->setEntityId($entity_id);
                    $block->setLanguageId($langArray[$lang]);
                    $block->setName($name);
                }

                $block->setValue($value);

                $this->getBlockTable()->save($block);
            }
        }
    }

    public function getBlocks($entity, $entity_type)
    {
        $languagesArray = $this->getLanguageTable()->getAsAssocArray();

        $blocks = $this->getBlockTable()->getBy(array('entity_id' => $entity->getId(), 'entity_type' => $entity_type));
        $values = array();

        /* @var $block Block */
        foreach($blocks as $block)
        {
            $lang = $languagesArray[$block->getLanguageId()];
            $values[$lang . '-' . $block->getName()] = $block->getValue();
        }

        return $values;
    }

    public function findBlocksForEntityByLanguage($entity, $entity_type, $lang)
    {

    }

    private function preg_array_key_exists($pattern, $array)
    {
        $keys = array_keys($array);
        return (int) preg_grep($pattern,$keys);
    }

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
    }


    /**
     * @return \CmsIr\System\Model\LanguageTable
     */
    public function getLanguageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\LanguageTable');
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
