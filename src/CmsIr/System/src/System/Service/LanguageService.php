<?php

namespace CmsIr\System\Service;

use CmsIr\System\Model\Status;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;

class LanguageService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function setLanguage($params)
    {
        $lang = isset($params['lang']) ? $params['lang'] : 'pl';

        $config = $this->getServiceLocator()->get('Config');
        $langArray = $config['languages'];

        if(!in_array($lang, $langArray)) {
            $lang = 'pl';
        }

        $loc = $this->getServiceLocator();
        $translator = $loc->get('translator');

        $translator->addTranslationFile("phparray",
            './module/Page/language/' . $lang . '.php');
        $loc->get('ViewHelperManager')->get('translate')
            ->setTranslator($translator);

        return $lang;
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
