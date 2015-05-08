<?php
namespace CmsIr\Menu\Model;

use CmsIr\System\Model\Model;

class MenuItem extends Model
{
    protected $id;
    protected $node_id;
    protected $label;
    protected $url;
    protected $position;
    protected $subtitle;

    public function exchangeArray($data) 
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->node_id = (!empty($data['node_id'])) ? $data['node_id'] : null;
        $this->label = (!empty($data['label'])) ? $data['label'] : null;
        $this->url = (!empty($data['url'])) ? $data['url'] : null;
        $this->position = (!empty($data['position'])) ? $data['position'] : 0;
        $this->subtitle = (!empty($data['subtitle'])) ? $data['subtitle'] : null;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $node_id
     */
    public function setNodeId($node_id)
    {
        $this->node_id = $node_id;
    }

    /**
     * @return mixed
     */
    public function getNodeId()
    {
        return $this->node_id;
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
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }



}