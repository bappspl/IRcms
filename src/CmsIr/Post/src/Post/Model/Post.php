<?php
namespace CmsIr\Post\Model;

use Zend\InputFilter\InputFilterInterface;
use CmsIr\System\Util\Inflector;

class Post
{
    protected $id;
    protected $name;
    protected $status_id;
    protected $category;
    protected $date;
    protected $author_id;
    protected $filename_main;

    /*virtual fields */
    protected $files;
    protected $blocks;
    protected $extra;
    protected $title;
    protected $content;
    protected $url;
    protected $author;

    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->status_id = (!empty($data['status_id'])) ? $data['status_id'] : null;
        $this->category = (!empty($data['category'])) ? $data['category'] : null;
        $this->date = (!empty($data['date'])) ? $data['date'] : null;
        $this->author_id = (!empty($data['author_id'])) ? $data['author_id'] : null;
        $this->filename_main = (!empty($data['filename_main'])) ? $data['filename_main'] : null;
        $this->extra = (!empty($data['extra'])) ? $data['extra'] : null;

        /*virtual fields */
        $this->files = (!empty($data['files'])) ? $data['files'] : null;
        $this->blocks = (!empty($data['blocks'])) ? $data['blocks'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->content = (!empty($data['content'])) ? $data['content'] : null;
        $this->url = (!empty($data['url'])) ? $data['url'] : null;
        $this->author = (!empty($data['author'])) ? $data['author'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


	protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {

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
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * @param mixed $author_id
     */
    public function setAuthorId($author_id)
    {
        $this->author_id = $author_id;
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
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param mixed $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
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
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param mixed $blocks
     */
    public function setBlocks($blocks)
    {
        $this->blocks = $blocks;
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
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }
}