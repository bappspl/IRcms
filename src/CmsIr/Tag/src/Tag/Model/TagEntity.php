<?php
namespace CmsIr\Tag\Model;

use CmsIr\System\Model\Model;

class TagEntity extends Model
{
    protected $id;
    protected $tag_id;
    protected $entity_id;
    protected $entity_type;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->tag_id = (!empty($data['tag_id'])) ? $data['tag_id'] : null;
        $this->entity_id = (!empty($data['entity_id'])) ? $data['entity_id'] : null;
        $this->entity_type = (!empty($data['entity_type'])) ? $data['entity_type'] : null;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTagId()
    {
        return $this->tag_id;
    }

    /**
     * @param mixed $tag_id
     */
    public function setTagId($tag_id)
    {
        $this->tag_id = $tag_id;
    }

    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->entity_id;
    }

    /**
     * @param mixed $entity_id
     */
    public function setEntityId($entity_id)
    {
        $this->entity_id = $entity_id;
    }

    /**
     * @return mixed
     */
    public function getEntityType()
    {
        return $this->entity_type;
    }

    /**
     * @param mixed $entity_type
     */
    public function setEntityType($entity_type)
    {
        $this->entity_type = $entity_type;
    }
}