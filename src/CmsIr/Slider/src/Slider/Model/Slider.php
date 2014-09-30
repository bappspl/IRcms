<?php
namespace CmsIr\Slider\Model;

use CmsIr\System\Model\Model;

class Slider extends Model
{
//    protected $id;
    protected $name;
    protected $slug;
    protected $status_id;

    //virtual

    protected $status;

    public function exchangeArray($data) 
    {
//        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : null;
        $this->status_id = (!empty($data['status_id'])) ? $data['status_id'] : 2;
    }

//    /**
//     * @param mixed $id
//     */
//    public function setId($id)
//    {
//        $this->id = $id;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getId()
//    {
//        return $this->id;
//    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
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
    public function getStatusId()
    {
        return $this->status_id;
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


}