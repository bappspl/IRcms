<?php

namespace CmsIr\Page\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CmsIr\Page\Entity\PageTable")
 * @ORM\Table(name="cms_page")
 */
class Page
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

    /** @ORM\Column(type="string") */
    protected $url;

    /**
    *  @ORM\ManyToOne(targetEntity="CmsIr\System\Entity\Status")
    *  @ORM\JoinColumn(name="status_id", referencedColumnName="id")
    */
    protected $status;

    /** @ORM\Column(type="text") */
    protected $content;

    /** @ORM\Column(type="string") */
    protected $filename_main;

    /**
     *  @ORM\OneToMany(targetEntity="CmsIr\File\Entity\File", mappedBy="page")
     **/
    protected $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
        $this->content = (!empty($data['content'])) ? $data['content'] : null; ;
        $this->filename_main = (!empty($data['filename_main'])) ? $data['filename_main'] : null; ;
        $this->url = (!empty($data['url'])) ? $data['url'] : null; ;
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return mixed
     */
    public function getFilenameMain()
    {
        return $this->filename_main;
    }

    /**
     * @param mixed $filename_main
     */
    public function setFilenameMain($filename_main)
    {
        $this->filename_main = $filename_main;
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
}