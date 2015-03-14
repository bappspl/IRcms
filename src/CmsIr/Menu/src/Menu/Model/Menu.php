<?php
namespace CmsIr\Menu\Model;

use CmsIr\System\Model\Model;

class Menu extends Model
{
    protected $id;
    protected $name;
    protected $machine_name;
    protected $website_id;
    protected $position;

    public function exchangeArray($data) 
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->machine_name = (!empty($data['machine_name'])) ? $data['machine_name'] : null;
        $this->website_id = (!empty($data['website_id'])) ? $data['website_id'] : null;
        $this->position = (!empty($data['position'])) ? $data['position'] : 0;
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
     * @param mixed $machineName
     */
    public function setMachineName($machineName)
    {
        $this->machine_name = $machineName;
    }

    /**
     * @return mixed
     */
    public function getMachineName()
    {
        return $this->machine_name;
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
    public function getName()
    {
        return $this->name;
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

}