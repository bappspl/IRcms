<?php
namespace CmsIr\Newsletter\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
// the object will be hydrated by Zend\Db\TableGateway\TableGateway
class NewsletterSettings
{
    protected $id;
    protected $sender_email;
    protected $sender;
    protected $footer;

    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->sender_email     = (!empty($data['sender_email'])) ? $data['sender_email'] : null;
        $this->sender     = (!empty($data['sender'])) ? $data['sender'] : null;
        $this->footer     = (!empty($data['footer'])) ? $data['footer'] : null;
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
     * @param mixed $footer
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
    }

    /**
     * @return mixed
     */
    public function getFooter()
    {
        return $this->footer;
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
     * @param mixed $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender_email
     */
    public function setSenderEmail($sender_email)
    {
        $this->sender_email = $sender_email;
    }

    /**
     * @return mixed
     */
    public function getSenderEmail()
    {
        return $this->sender_email;
    }


}