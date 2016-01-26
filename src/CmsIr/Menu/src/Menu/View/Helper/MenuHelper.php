<?php

namespace CmsIr\Menu\View\Helper;

use CmsIr\Menu\Model\MenuNode;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;

class MenuHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function getRoute() {
        $routeMatch = $this->serviceLocator->getServiceLocator()->get('request')->getUri()->getPath();
        return $routeMatch;
    }

    public function renderMenu($menu, $ulClass = null, $ulId = null, $liClass = null, $subUlClass = null, $subLiClass = null, $langId = null, $lang = null)
    {
        $route = $this->getRoute();
        $template = ' <ul class="nav navbar-nav az-menu-wrapper">';
        foreach($menu as $item) {
            if(is_array($item->getItems())) {
                $subItems = $item->getItems();
                $fistItem = end($subItems);
                $checkUrl = $fistItem->getUrl();
                $pos = strpos($route, $checkUrl);
            } else {
                $checkUrl = $item->getItems()->getUrl();
                $pos = strpos($route, $checkUrl);
            }
            $template .= '<li>';
            if($pos !== false && $checkUrl !== '/') {
                $active = 'active';
            } else {
                if($checkUrl == '/' && strlen($route) == 1)
                {
                    $active = 'active';
                } else {
                    $active = '';
                }
            }
            if(is_array($item->getItems())) {
                $subItems = $item->getItems();
                $fistItem = end($subItems);
                $label = $fistItem->getLabel();
                $url = $fistItem->getUrl();
                array_pop($subItems);
                $template .= '<a href="' . $url . '" class="' . $active . '">'.$label.'</a>';
                $template .= '<ul class="sub-menu">';
                foreach($subItems as $subItem) {
                    $subItemNode = $subItem->getItems();
                    $label = $subItemNode->getLabel();
                    $url = $subItemNode->getUrl();
                    $template .= '<li><a href="'.$url.'">'.$label.'</a></li>';
                }
                $template .= '</ul>';
            } else {
                $subItem = $item->getItems();
                $label = $subItem->getLabel();
                $url = $subItem->getUrl();
                $template .= '<a href="'.$url.'" class="' . $active . '">'.$label.'</a>';
            }
            $template .= '</li>';
        }
        // $template .= $this->addExtraOptions();
        $template .= '</ul>';
        return $template;
    }

    public function renderSecondMenu($menu, $ulClass = null, $liClass = null, $ulId = null, $subUlClass = null, $subLiClass = null)
    {
        $route = $this->getRoute();
        $template = ' <ul class="nav navbar-nav az-menu-wrapper">';
        foreach($menu as $item) {
            if(is_array($item->getItems())) {
                $subItems = $item->getItems();
                $fistItem = end($subItems);
                $checkUrl = $fistItem->getUrl();
                $pos = strpos($route, $checkUrl);
            } else {
                $checkUrl = $item->getItems()->getUrl();
                $pos = strpos($route, $checkUrl);
            }
            $template .= '<li>';
            if($pos !== false && $checkUrl !== '/') {
                $active = 'active';
            } else {
                if($checkUrl == '/' && strlen($route) == 1)
                {
                    $active = 'active';
                } else {
                    $active = '';
                }
            }
            if(is_array($item->getItems())) {
                $subItems = $item->getItems();
                $fistItem = end($subItems);
                $label = $fistItem->getLabel();
                $url = $fistItem->getUrl();
                array_pop($subItems);
                $template .= '<a href="' . $url . '" class="' . $active . '">'.$label.'</a>';
                $template .= '<ul class="sub-menu">';
                foreach($subItems as $subItem) {
                    $subItemNode = $subItem->getItems();
                    $label = $subItemNode->getLabel();
                    $url = $subItemNode->getUrl();
                    $template .= '<li><a href="'.$url.'">'.$label.'</a></li>';
                }
                $template .= '</ul>';
            } else {
                $subItem = $item->getItems();
                $label = $subItem->getLabel();
                $url = $subItem->getUrl();
                $template .= '<a href="'.$url.'" class="' . $active . '">'.$label.'</a>';
            }
            $template .= '</li>';
        }
        // $template .= $this->addExtraOptions();
        $template .= '</ul>';
        return $template;
    }

    public function addExtraOptions()
    {
        $extraFields = '<li class="parent right-icon">
                            <i class="fa fa-phone" id="nav-icon-phone"></i>
                        </li>';
        return $extraFields;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @return \CmsIr\Page\Model\PageTable
     */
    public function getPageTable()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('CmsIr\Page\Model\PageTable');
    }

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
    }

    public function translate($word)
    {
        return $this->getServiceLocator()->getServiceLocator()->get('translator')->translate($word);
    }
}