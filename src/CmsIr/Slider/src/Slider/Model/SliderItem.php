<?php
namespace CmsIr\Slider\Model;

use CmsIr\System\Model\Model;
use CmsIr\System\Util\Inflector;

class SliderItem extends Model
{
    protected $id;
    protected $slider_id;
    protected $name;
//    protected $description;
    protected $filename;
    protected $status_id;
    protected $position;
//    protected $url;

    //virtual

    protected $status;
    protected $title;
    protected $subtitle;

    public function exchangeArray($data) 
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->slider_id = (!empty($data['slider_id'])) ? $data['slider_id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->filename = (!empty($data['filename'])) ? $data['filename'] : null;
        $this->status_id = (!empty($data['status_id'])) ? $data['status_id'] : null;
        $this->position = (!empty($data['position'])) ? $data['position'] : 0;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->subtitle = (!empty($data['subtitle'])) ? $data['subtitle'] : null;
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
    public function getSliderId()
    {
        return $this->slider_id;
    }

    /**
     * @param mixed $slider_id
     */
    public function setSliderId($slider_id)
    {
        $this->slider_id = $slider_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getStatusId()
    {
        return $this->status_id;
    }

    /**
     * @param mixed $status_id
     */
    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param mixed $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }
}