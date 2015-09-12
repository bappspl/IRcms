<?php

namespace CmsIr\System\View\Helper;

use Zend\Form\Exception;
use Zend\View\Helper\AbstractHelper;

class Language extends AbstractHelper
{
    protected $serviceLocator;

    public function __invoke($partial, $values = array())
    {
        $languages = $this->getLanguageTable()->getAll();

        $htmlOutput = $this->getView()->render($partial, array('values' => $values, 'languages' => $languages));

        return $htmlOutput;
    }

    /**
     * @return \CmsIr\System\Model\LanguageTable
     */
    public function getLanguageTable()
    {
        return $this->getView()->getHelperPluginManager()->getServiceLocator()->get('CmsIr\System\Model\LanguageTable');
    }
}