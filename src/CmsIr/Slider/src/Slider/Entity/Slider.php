<?php

namespace CmsIr\Slider\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CmsIr\Slider\Entity\SliderTable")
 * @ORM\Table(name="cms_slider")
 */
class Slider
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $name;

    /** @ORM\Column(type="string") */
    protected $slug;

    /**
    *  @ORM\ManyToOne(targetEntity="CmsIr\System\Entity\Status")
    *  @ORM\JoinColumn(name="status_id", referencedColumnName="id")
    */
    protected $status;

    /**
     *  @ORM\OneToMany(targetEntity="CmsIr\Slider\Entity\SliderItem", mappedBy="slider")
     **/
    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
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
    public function getSlug()
    {
        return $this->slug;
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
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }
}