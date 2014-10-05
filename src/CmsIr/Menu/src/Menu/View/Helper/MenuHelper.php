<?php

namespace CmsIr\Menu\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MenuHelper extends AbstractHelper
{
    public function renderMenu($menu, $ulClass = null, $liClass = null, $subUlClass = null, $subLiClass = null)
    {
        $template = '<ul class="'.$ulClass.'">';

        foreach($menu as $item) {
            $template .= '<li class="'.$liClass.'">';
            if(is_array($item->getItems())) {
                $subItems = $item->getItems();

                $fistItem = end($subItems);
                $label = $fistItem->getLabel();
                $url = $fistItem->getUrl();

                array_pop($subItems);
                $template .= '<a href="'.$url.'">'.$label.'</a>';
                $template .= '<ul class="'.$subUlClass.'">';

                foreach($subItems as $subItem) {
                    $subItemNode = $subItem->getItems();
                    $label = $subItemNode->getLabel();
                    $url = $subItemNode->getUrl();
                    $template .= '<li class="'.$subLiClass.'"><a href="'.$url.'">'.$label.'</a></li>';
                }

                $template .= '</ul>';


            } else {
                $subItem = $item->getItems();

                $label = $subItem->getLabel();
                $url = $subItem->getUrl();
                $template .= '<a href="'.$url.'">'.$label.'</a>';
            }
            $template .= '</li>';
        }

        $template .= $this->addExtraOptions();

        $template .= '</ul>';
        return $template;
    }

    public function addExtraOptions()
    {
        $extraFields = '<li class="parent right-icon">
                            <i class="fa fa-search" id="nav-icon-search"></i>
                        </li>
                        <li class="parent right-icon">
                            <i class="fa fa-phone" id="nav-icon-phone"></i>
                        </li>';
        return $extraFields;
    }
}