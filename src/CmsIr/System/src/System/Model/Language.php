<?php

namespace CmsIr\System\Model;

class Language
{
    protected $id;
    protected $name;
    protected $shortcut;
    protected $url_shortcut;
    protected $filename;

    public function exchangeArray($data)
    {
        $this->id   = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->shortcut = (!empty($data['shortcut'])) ? $data['shortcut'] : null;
        $this->url_shortcut = (!empty($data['url_shortcut'])) ? $data['url_shortcut'] : null;
        $this->filename = (!empty($data['filename'])) ? $data['filename'] : null;
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
    public function getShortcut()
    {
        return $this->shortcut;
    }

    /**
     * @param mixed $shortcut
     */
    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;
    }

    /**
     * @return mixed
     */
    public function getUrlShortcut()
    {
        return $this->url_shortcut;
    }

    /**
     * @param mixed $url_shortcut
     */
    public function setUrlShortcut($url_shortcut)
    {
        $this->url_shortcut = $url_shortcut;
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

}