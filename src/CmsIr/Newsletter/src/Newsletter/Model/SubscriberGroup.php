<?php
namespace CmsIr\Newsletter\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
// the object will be hydrated by Zend\Db\TableGateway\TableGateway
class SubscriberGroup
{
    protected $id;
    protected $name;
    protected $slug;
    protected $description;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->name     = (!empty($data['name'])) ? $data['name'] : null;
        $this->slug     = (!empty($data['slug'])) ? $data['slug'] : null;
        $this->description     = (!empty($data['description'])) ? $data['description'] : null;
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
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

}