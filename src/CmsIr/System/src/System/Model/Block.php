<?php

namespace CmsIr\System\Model;

class Block
{
    protected $id;
    protected $entity_id;
    protected $entity_type;
    protected $language_id;
    protected $content;
    protected $created;
    protected $user_created;
    protected $modified;
    protected $user_modified;

    public function exchangeArray($data)
    {
        $this->id   = (!empty($data['id'])) ? $data['id'] : null;
        $this->entity_id   = (!empty($data['entity_id'])) ? $data['entity_id'] : null;
        $this->entity_type   = (!empty($data['entity_type'])) ? $data['entity_type'] : null;
        $this->language_id   = (!empty($data['language_id'])) ? $data['language_id'] : null;
        $this->content   = (!empty($data['content'])) ? $data['content'] : null;
        $this->created   = (!empty($data['created'])) ? $data['created'] : null;
        $this->user_created   = (!empty($data['user_created'])) ? $data['user_created'] : null;
        $this->modified   = (!empty($data['modified'])) ? $data['modified'] : null;
        $this->user_modified   = (!empty($data['user_modified'])) ? $data['user_modified'] : null;
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

    /**
     * @return mixed
     */
    public function getLanguageId()
    {
        return $this->language_id;
    }

    /**
     * @param mixed $language_id
     */
    public function setLanguageId($language_id)
    {
        $this->language_id = $language_id;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getUserCreated()
    {
        return $this->user_created;
    }

    /**
     * @param mixed $user_created
     */
    public function setUserCreated($user_created)
    {
        $this->user_created = $user_created;
    }

    /**
     * @return mixed
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param mixed $modified
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    /**
     * @return mixed
     */
    public function getUserModified()
    {
        return $this->user_modified;
    }

    /**
     * @param mixed $user_modified
     */
    public function setUserModified($user_modified)
    {
        $this->user_modified = $user_modified;
    }
}