<?php

namespace CmsIr\System\Model;

class LogEvent extends Model
{
    protected  $id;
    protected  $entity_id;
    protected  $entity_type;
    protected  $what;
    protected  $action;
    protected  $description;
    protected  $user;
    protected  $date;
    protected  $viewed;

    protected $filename;

    public function exchangeArray($data)
    {
        $this->id   = (!empty($data['id'])) ? $data['id'] : null;
        $this->entity_id = (!empty($data['entity_id'])) ? $data['entity_id'] : null;
        $this->entity_type = (!empty($data['entity_type'])) ? $data['entity_type'] : null;
        $this->what = (!empty($data['what'])) ? $data['what'] : null;
        $this->action = (!empty($data['action'])) ? $data['action'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->user = (!empty($data['user'])) ? $data['user'] : null;
        $this->date = (!empty($data['date'])) ? $data['date'] : null;
        $this->viewed = (!empty($data['viewed'])) ? $data['viewed'] : null;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * @param mixed $viewed
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;
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
    public function getWhat()
    {
        return $this->what;
    }

    /**
     * @param mixed $what
     */
    public function setWhat($what)
    {
        $this->what = $what;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

}