<?php

namespace CmsIr\System\Logger;

use CmsIr\System\Model\Status;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Log\Writer\Stream;
use Zend\Mail\Message;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;

class Logger implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function logException($exception)
    {
        $logger = new \Zend\Log\Logger();
        $writer = new Stream('./data/log/'.date('Y-m-d').'-error.log');
        $logger->addWriter($writer);
        $logger->log(\Zend\Log\Logger::CRIT, $exception);

        $config = $this->getServiceLocator()->get('Config');
        $appName = $config['app_name'];
        $loggerMail = $config['logger_mail'];
        

        if($loggerMail === true) {
            $transport = $this->getServiceLocator()->get('mail.transport')->findMailConfig();
            $from = $this->getServiceLocator()->get('mail.transport')->findFromMail();

            $message = new Message();
            $this->getServiceLocator()->get('Request')->getServer();
            $message->addTo('logger@web-ir.pl')
                ->addFrom($from)
                ->setSubject($appName)
                ->setBody($exception);
            $message->setEncoding('UTF-8');
            $transport->send($message);
        }

        return $logger;
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
