<?php
namespace CmsIr\Menu\Model;

use CmsIr\System\Model\Model;

class MenuNode extends Model
{
    protected $id;
    protected $tree_id;
    protected $parent_id;
    protected $depth;
    protected $is_visible;
    protected $provider_type;
    protected $settings;
    protected $position;

    protected $items;

    public function exchangeArray($data) 
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->tree_id = (!empty($data['tree_id'])) ? $data['tree_id'] : null;
        $this->parent_id = (!empty($data['parent_id'])) ? $data['parent_id'] : null;
        $this->depth = (!empty($data['depth'])) ? $data['depth'] : null;
        $this->is_visible = (!empty($data['is_visible'])) ? $data['is_visible'] : null;
        $this->provider_type = (!empty($data['provider_type'])) ? $data['provider_type'] : null;
        $this->settings = (!empty($data['settings'])) ? $data['settings'] : null;
        $this->position = (!empty($data['position'])) ? $data['position'] : 0;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $depth
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
    }

    /**
     * @return mixed
     */
    public function getDepth()
    {
        return $this->depth;
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
     * @param mixed $is_visible
     */
    public function setIsVisible($is_visible)
    {
        $this->is_visible = $is_visible;
    }

    /**
     * @return mixed
     */
    public function getIsVisible()
    {
        return $this->is_visible;
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
    public function getParentId()
    {
        return $this->parent_id;
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
     * @param mixed $provider_type
     */
    public function setProviderType($provider_type)
    {
        $this->provider_type = $provider_type;
    }

    /**
     * @return mixed
     */
    public function getProviderType()
    {
        return $this->provider_type;
    }

    /**
     * @param mixed $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param mixed $tree_id
     */
    public function setTreeId($tree_id)
    {
        $this->tree_id = $tree_id;
    }

    /**
     * @return mixed
     */
    public function getTreeId()
    {
        return $this->tree_id;
    }



}