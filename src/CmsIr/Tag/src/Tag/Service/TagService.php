<?php

namespace CmsIr\Tag\Service;

use CmsIr\Category\Model\Category;
use CmsIr\Tag\Model\Tag;
use CmsIr\Tag\Model\TagEntity;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TagService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findAsAssocArray()
    {
        /* @var $tag Tag */
        $tags = $this->getTagTable()->getAll();

        $tagArray = array();

        foreach($tags as $tag) {
            $tagArray[$tag->getName()] = $tag->getName();
        }

        return $tagArray;
    }

    public function saveTags($tagArray, $entity_id, $entity_type)
    {
        $updatedTags = array();

        foreach($tagArray as $tag) {
            /* @var $foundTag Tag */
            $foundTag = $this->getTagTable()->getOneBy(array('name' => $tag));

            if($foundTag) {
                $id = $foundTag->getId();
            } else {
                $newTag = new Tag();
                $newTag->setName($tag);

                $id = $this->getTagTable()->save($newTag);

                $postArray = array('pl-name' => $tag);

                $this->getBlockService()->saveBlocks($id, 'Tag', $postArray, 'title');
            }

            /* @var $foundTagEntity TagEntity */
            $foundTagEntity = $this->getTagEntityTable()->getOneBy(array('tag_id' => $id, 'entity_id' => $entity_id, 'entity_type' => $entity_type));

            if($foundTagEntity) {
                array_push($updatedTags, $foundTagEntity->getId());
            } else {
                $tagEntity = new TagEntity();
                $tagEntity->setTagId($id);
                $tagEntity->setEntityId($entity_id);
                $tagEntity->setEntityType($entity_type);

                $entityId = $this->getTagEntityTable()->save($tagEntity);

                array_push($updatedTags, $entityId);
            }
        }

        $allTagEntites = $this->getTagEntityTable()->getBy(array('entity_id' => $entity_id, 'entity_type' => $entity_type));

        /* @var $t TagEntity */
        foreach($allTagEntites as $t) {
            $tId = $t->getId();

            if(!in_array($tId, $updatedTags)) {
                $this->getTagEntityTable()->deleteTagEntity($tId);
            }
        }
    }

    public function findAsAssocArrayForEntity($entity_id, $entity_type)
    {
        $allTagEntites = $this->getTagEntityTable()->getBy(array('entity_id' => $entity_id, 'entity_type' => $entity_type));

        $tags = array();

        /* @var $tagEntity TagEntity */
        foreach($allTagEntites as $tagEntity) {
            $tagId = $tagEntity->getTagId();

            /* @var $tag Tag */
            $tag = $this->getTagTable()->getOneBy(array('id' => $tagId));

            array_push($tags, $tag->getName());
        }

        return $tags;
    }

    /**
     * @return \CmsIr\System\Model\LanguageTable
     */
    public function getLanguageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\LanguageTable');
    }

    /**
     * @return \CmsIr\Tag\Model\TagTable
     */
    public function getTagTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Tag\Model\TagTable');
    }

    /**
     * @return \CmsIr\Tag\Model\TagEntityTable
     */
    public function getTagEntityTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Tag\Model\TagEntityTable');
    }

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
    }

    /**
     * @return \CmsIr\System\Service\BlockService
     */
    public function getBlockService()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Service\BlockService');
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
