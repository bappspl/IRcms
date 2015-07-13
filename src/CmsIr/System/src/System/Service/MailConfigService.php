<?php

namespace CmsIr\System\Service;

use CmsIr\System\Model\Status;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

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
        $options = $this->getMailConfigTable()->getOneBy(array('id' => 1));

        $username = $options->getUsername();
        return $username;
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
