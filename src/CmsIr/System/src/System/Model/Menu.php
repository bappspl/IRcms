<?php

namespace CmsIr\System\Model;

class Menu
{
    protected $id;
    protected $name;
    protected $label;
    protected $route;
    protected $class;
    protected $access;
    protected $visible_in_primary;
    protected $parent_id;
    protected $params;
    protected $website_id;

    public function exchangeArray($data)
    {
        $this->id   = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->label = (!empty($data['label'])) ? $data['label'] : null;
        $this->route = (!empty($data['route'])) ? $data['route'] : null;
        $this->class = (!empty($data['class'])) ? $data['class'] : null;
        $this->access = (!empty($data['access'])) ? $data['access'] : null;
        $this->visible_in_primary = (!empty($data['visible_in_primary'])) ? $data['visible_in_primary'] : null;
        $this->parent_id = (!empty($data['parent_id'])) ? $data['parent_id'] : null;
        $this->params = (!empty($data['params'])) ? $data['params'] : null;
        $this->website_id = (!empty($data['website_id'])) ? $data['website_id'] : null;
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
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @param mixed $access
     */
    public function setAccess($access)
    {
        $this->access = $access;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
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
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param mixed $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getVisibleInPrimary()
    {
        return $this->visible_in_primary;
    }

    /**
     * @param mixed $visible_in_primary
     */
    public function setVisibleInPrimary($visible_in_primary)
    {
        $this->visible_in_primary = $visible_in_primary;
    }

    /**
     * @return mixed
     */
    public function getWebsiteId()
    {
        return $this->website_id;
    }

    /**
     * @param mixed $website_id
     */
    public function setWebsiteId($website_id)
    {
        $this->website_id = $website_id;
    }
}