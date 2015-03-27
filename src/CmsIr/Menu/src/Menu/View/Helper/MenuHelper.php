<?php

namespace CmsIr\Menu\View\Helper;

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

    public function renderMenu($menu, $ulClass = null, $liClass = null, $ulId = null, $subUlClass = null, $subLiClass = null)
    {
        $route = $this->getRoute();

        $template = '<ul class="'.$ulClass.'" id="'.$ulId.'">';

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

            $template .= '<li class="'.$liClass.' dropdown">';

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
                $filename = $fistItem->getFilename();

                array_pop($subItems);

                if(!empty($filename))
                {
                    $template .= '<a href="#"' . $active . '">' .
                        '<img src="/files/menu/' . $filename . '">' .
                        $label .
                        '<span class="arrow"></span></a>';
                } else
                {
                    $template .= '<a href="#"' . $active . '">' . $label . '<span class="arrow"></span></a>';
                }


                $template .= '<ul>';

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
                $filename = $subItem->getFilename();
                $template .= '<a href="'.$url.'" class="' . $active . '"><img src="/files/menu/' . $filename . '">'.$label.'</a>';
            }
            $template .= '</li>';
        }
        // $template .= $this->addExtraOptions();

        $template .= '</ul>';
        return $template;
    }

    public function renderFooterMenu($menu, $ulClass = null, $liClass = null, $ulId = null, $subUlClass = null, $subLiClass = null)
    {
        $route = $this->getRoute();

        $template = '<ul class="'.$ulClass.'" id="'.$ulId.'">';

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
                $filename = $fistItem->getFilename();

                array_pop($subItems);


                $template .= '<a class="bold" href="#"' . $active . '">' . $label . '</a><span>-</span>';


                $template .= '<ul>';

                foreach($subItems as $subItem) {
                    $subItemNode = $subItem->getItems();
                    $label = $subItemNode->getLabel();
                    $url = $subItemNode->getUrl();

                    $template .= '<li><a href="'.$url.'">'.$label.'</a> - </li>';
                }

                $template .= '</ul>';


            } else {
                $subItem = $item->getItems();

                $label = $subItem->getLabel();
                $url = $subItem->getUrl();
                $template .= '<a href="'.$url.'" class="bold ' . $active . '">'.$label.'</a> - ';
            }
            $template .= '</li>';
        }
        // $template .= $this->addExtraOptions();

        $template .= '</ul>';
        return $template;
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
}