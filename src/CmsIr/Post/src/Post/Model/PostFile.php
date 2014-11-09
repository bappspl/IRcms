<?php
namespace CmsIr\Post\Model;

use Zend\InputFilter\InputFilterInterface;

class PostFile
{
    protected $id;
    protected $filename;
    protected $post_id;
    protected $size;


    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->filename = (!empty($data['filename'])) ? $data['filename'] : null;
        $this->post_id = (!empty($data['post_id'])) ? $data['post_id'] : null;
        $this->size = (!empty($data['size'])) ? $data['size'] : null;
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
    public function getFilename()
    {
        return $this->filename;
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
     * @param mixed $post_id
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }


}