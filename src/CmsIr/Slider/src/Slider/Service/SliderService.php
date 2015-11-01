<?php

namespace CmsIr\Slider\Service;

use CmsIr\Slider\Model\Slider;
use CmsIr\Slider\Model\SliderItem;
use CmsIr\System\Model\Block;
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

    public function findOneBySlug($slug, $langId)
    {
        /* @var $slider Slider */
        $slider = $this->getSliderTable()->getOneBy(array('slug' => $slug));

        $items = $this->getSliderItemTable()->getBy(array('slider_id' => $slider->getId(), 'status_id' => 1), 'position ASC');

        /* @var $item SliderItem */
        foreach($items as $item) {
            $blocks = $this->getBlockTable()->getBy(array('entity_type' => 'SliderItem', 'language_id' => $langId, 'entity_id' => $item->getId()));

            /* @var $block Block */
            foreach($blocks as $block) {
                $fieldName = $block->getName();

                switch ($fieldName) {
                    case 'title':
                        $item->setTitle($block->getValue());
                        break;
                    case 'subtitle':
                        $item->setSubtitle($block->getValue());
                        break;
                }
            }
        }

        $slider->setItems($items);

        return $slider;
    }

    /**
     * @return \CmsIr\Slider\Model\SliderTable
     */
    public function getSliderTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Model\SliderTable');
    }

    /**
     * @return \CmsIr\Slider\Model\SliderItemTable
     */
    public function getSliderItemTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Model\SliderItemTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
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
