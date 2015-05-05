<?php
namespace CmsIr\File\Model;

use CmsIr\System\Model\Model;

class File extends Model
{
    protected $id;
    protected $entity_id;
    protected $entity_type;
    protected $filename;
    protected $mime_type;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->entity_id = (!empty($data['entity_id'])) ? $data['entity_id'] : null;
        $this->entity_type = (!empty($data['entity_type'])) ? $data['entity_type'] : null;
        $this->filename = (!empty($data['filename'])) ? $data['filename'] : null;
        $this->mime_type = (!empty($data['mime_type'])) ? $data['mime_type'] : null;
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
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * @param mixed $mime_type
     */
    public function setMimeType($mime_type)
    {
        $this->mime_type = $mime_type;
    }

}