<?php

namespace CmsIr\Banner\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CmsIr\Banner\Entity\BannerTable")
 * @ORM\Table(name="cms_banner")
 */
class Banner
{
    const ENTITY = 'CmsIr\Banner\Entity\Banner';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="integer") */
    protected $status_id;

    /** @ORM\Column(type="string") */
    protected $name;

    /** @ORM\Column(type="integer") */
    protected $entity_type_id;

    /** @ORM\Column(type="string") */
    protected $slug;

    /** @ORM\Column(type="string") */
    protected $url;

    /** @ORM\Column(type="text") */
    protected $filename;

    /** @ORM\Column(type="string") */
    protected $target;

    /** @ORM\Column(type="integer") */
    protected $position;

    /** @ORM\Column(type="integer") */
    protected $removed;

    /** @ORM\Column(type="datetime") */
    protected $date_creating;

    /** @ORM\Column(type="datetime") */
    protected $date_editing;

    /** @ORM\Column(type="datetime") */
    protected $date_removing;

    /**
     *  @ORM\ManyToOne(targetEntity="CmsIr\System\Entity\Status")
     *  @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     *  @ORM\ManyToOne(targetEntity="CmsIr\System\Entity\EntityType")
     *  @ORM\JoinColumn(name="entity_type_id", referencedColumnName="id")
     */
    protected $entity_type;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : null;
        $this->url = (!empty($data['url'])) ? $data['url'] : null;
        $this->filename = (!empty($data['filename'])) ? $data['filename'] : null;
        $this->target = (!empty($data['target'])) ? $data['target'] : null;
        $this->position = (!empty($data['position'])) ? $data['position'] : null;
        $this->removed = (!empty($data['removed'])) ? $data['removed'] : null;
        $this->date_creating = (!empty($data['date_creating'])) ? $data['date_creating'] : null;
        $this->date_editing = (!empty($data['date_editing'])) ? $data['date_editing'] : null;
        $this->date_removing = (!empty($data['date_removing'])) ? $data['date_removing'] : null;
        $this->entity_type = (!empty($data['entity_type'])) ? $data['entity_type'] : null;
        $this->status_id = (!empty($data['status_id'])) ? $data['status_id'] : null;
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
    public function getUrl()
    {
        return $this->url;
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
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
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
    public function getRemoved()
    {
        return $this->removed;
    }

    /**
     * @param mixed $removed
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;
    }

    /**
     * @return mixed
     */
    public function getDateCreating()
    {
        return $this->date_creating;
    }

    /**
     * @param mixed $date_creating
     */
    public function setDateCreating($date_creating)
    {
        $this->date_creating = $date_creating;
    }

    /**
     * @return mixed
     */
    public function getDateEditing()
    {
        return $this->date_editing;
    }

    /**
     * @param mixed $date_editing
     */
    public function setDateEditing($date_editing)
    {
        $this->date_editing = $date_editing;
    }

    /**
     * @return mixed
     */
    public function getDateRemoving()
    {
        return $this->date_removing;
    }

    /**
     * @param mixed $date_removing
     */
    public function setDateRemoving($date_removing)
    {
        $this->date_removing = $date_removing;
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
    public function getEntityTypeId()
    {
        return $this->entity_type_id;
    }

    /**
     * @param mixed $entity_type_id
     */
    public function setEntityTypeId($entity_type_id)
    {
        $this->entity_type_id = $entity_type_id;
    }
}