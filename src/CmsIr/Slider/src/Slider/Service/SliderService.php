<?php

namespace CmsIr\Slider\Service;

use CmsIr\Slider\Model\Slider;
use CmsIr\System\Model\Status;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;

class SliderService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function findAll()
    {
        $sliders = $this->getSliderTable()->getAll();

        /* @var $slider Slider */
        /* @var $status Status */
        foreach($sliders as $slider){
            $status = $this->getStatusTable()->getOneBy(array('id' => $slider->getStatusId()));
            $slider->setStatus($status->getName());
        }

        return $sliders;
    }

    /**
     * @return \CmsIr\Slider\Model\SliderTable
     */
    public function getSliderTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Model\SliderTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
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
