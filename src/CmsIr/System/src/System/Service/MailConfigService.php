<?php

namespace CmsIr\System\Service;

use CmsIr\System\Model\MailConfig;
use CmsIr\System\Model\Status;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Mail\Message;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

class MailConfigService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findMailConfig()
    {
        $options = $this->getMailConfigTable()->generateMailConfigArray();

        $transport = new Smtp();
        $transport->setOptions(new SmtpOptions($options));
        return $transport;
    }

    public function findFromMail()
    {
        /* @var $options MailConfig */
        $options = $this->getMailConfigTable()->getOneBy(array('id' => 1));

        $username = $options->getUsername();
        return $username;
    }

    public function findToMail()
    {
        /* @var $options MailConfig */
        $options = $this->getMailConfigTable()->getOneBy(array('id' => 1));

        $username = $options->getSend();
        return $username;
    }

    public function send($subject, $body, $to = null)
    {
        $transport = $this->findMailConfig();
        $from = $this->findFromMail();

        $to = isset($to) ? $to : $this->findToMail();

        $html = new MimePart($body);
        $html->type = "text/html";
        $html->charset = 'utf-8';

        $body = new MimeMessage();
        $body->setParts(array($html));

        $message = new Message();
        $message->addTo($to)
            ->addFrom($from)
            ->setEncoding('UTF-8')
            ->setSubject($subject)
            ->setBody($body);

        $transport->send($message);
    }

    /**
     * @return \CmsIr\System\Model\MailConfigTable
     */
    public function getMailConfigTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\MailConfigTable');
    }

    /**
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}
