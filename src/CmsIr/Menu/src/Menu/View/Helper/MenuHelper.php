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

        $template = '<ul class="'.$ulClass.'" id="'.$ulId.'">';

        // $item == $node
        /* @var $item MenuNode */
        foreach($menu as $item)
        {
            if(is_array($item->getItems()))
            {
                $subItems = $item->getItems();

                $fistItem = end($subItems);
                $checkUrl = $fistItem->getUrl();

                $pos = strpos($route, $checkUrl);
            } else
            {

                $checkUrl = $item->getItems()->getUrl();
                $pos = strpos($route, $checkUrl);
            }

            if($pos !== false && $checkUrl !== '/')
            {
                $active = 'active';
            } else
            {
                if($checkUrl == '/' && strlen($route) == 1)
                {
                    $active = 'active';
                } else {
                    $active = '';
                }
            }

            $template .= '<li class="'. $liClass . '">';

            if(is_array($item->getItems()))
            {
                $subItems = $item->getItems();

                $fistItem = end($subItems);

                //lang

                if($item->getProviderType() == 'Page' && $langId !== null)
                {
                    $label = $fistItem->getLabel();
                    $pageId = $this->getPageTable()->getOneBy(array('name' => $label))->getId();
                    $label = $this->getBlockTable()->getOneBy(array('entity_type' => 'Page', 'entity_id' => $pageId, 'language_id' => $langId, 'name' => 'title'))->getValue();
                    $url = $this->getBlockTable()->getOneBy(array('entity_type' => 'Page', 'entity_id' => $pageId, 'language_id' => $langId, 'name' => 'url'))->getValue();
                    $url = '/' . $lang . '/strona/' . $url;
                } else
                {
                    $url = $fistItem->getUrl();
                    $label = $fistItem->getLabel();
                }

                $subtitle = $fistItem->getSubtitle();
                array_pop($subItems);

                $template .= '<a class="' . $active . ' mn-has-sub"" href="' . $url . '" title="'.$subtitle.'">' . $label . ' <i class="fa fa-angle-down"></i></a>';

                $template .= '<ul class="mn-sub mn-has-multi"><li class="mn-sub-multi"><ul>';

                foreach($subItems as $subItem) {
                    $subItemNode = $subItem->getItems();

                    if($item->getProviderType() == 'Page' && $langId !== null)
                    {
                        $label = $fistItem->getLabel();
                        $pageId = $this->getPageTable()->getOneBy(array('name' => $label))->getId();
                        $label = $this->getBlockTable()->getOneBy(array('entity_type' => 'Page', 'entity_id' => $pageId, 'language_id' => $langId, 'name' => 'title'))->getValue();
                        $url = $this->getBlockTable()->getOneBy(array('entity_type' => 'Page', 'entity_id' => $pageId, 'language_id' => $langId, 'name' => 'url'))->getValue();
                        $url = '/' . $lang . '/strona/' . $url;
                    } else
                    {
                        $label = $fistItem->getLabel();
                        $url = $fistItem->getUrl();
                    }

                    $template .= '<li><a href="'.$url.'">'.$label.'</a></li>';
                }

                $template .= '</li></ul></li></ul>';


            } else {
                $subItem = $item->getItems();

                if($item->getProviderType() == 'Page' && $langId !== null)
                {
                    $label = $subItem->getLabel();                    ;
                    $pageId = $this->getPageTable()->getOneBy(array('name' => $label))->getId();
                    $label = $this->getBlockTable()->getOneBy(array('entity_type' => 'Page', 'entity_id' => $pageId, 'language_id' => $langId, 'name' => 'title'))->getValue();
                    $url = $this->getBlockTable()->getOneBy(array('entity_type' => 'Page', 'entity_id' => $pageId, 'language_id' => $langId, 'name' => 'url'))->getValue();
                    $url = '/' . $lang . '/strona/' . $url;
                } else
                {
                    $label = $this->translate($subItem->getLabel());
                    $url = '/' . $lang . $subItem->getUrl();
                }

                $subtitle = $subItem->getSubtitle();
                $template .= '<a href="'.$url.'" class="' . $active . ' mn-has-sub"" title="'.$subtitle.'">'.$label.'</a>';
            }
            $template .= '</li>';
        }
        // $template .= $this->addExtraOptions();

        $template .= '<li><a>&nbsp;</a></li>
            <!-- End Divider -->

            <!-- Search -->
            <li>
                <a href="#" class="mn-has-sub"><i class="fa fa-search"></i>'.$this->translate('Szukaj').'</a>

                <ul class="mn-sub">

                    <li>
                        <div class="mn-wrap">
                            <form method="post" class="form">
                                <div class="search-wrap">
                                    <button class="search-button animate" type="submit" title="'.$this->translate('Szukaj').'">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input type="text" class="form-control search-field" placeholder="'.$this->translate('Szukaj').'">
                                </div>
                            </form>
                        </div>
                    </li>

                </ul>

            </li>
            <!-- End Search -->

            <!-- Languages -->
           ';

//        $template .= '</ul>';
        return $template;
    }

    public function renderSecondMenu($menu, $ulClass = null, $liClass = null, $ulId = null, $subUlClass = null, $subLiClass = null)
    {
        $route = $this->getRoute();

        $menuMain = array_chunk($menu, 3);

        $template = '';


        foreach($menuMain as $menuSmall)
        {

            $template .= '<row>';

            foreach($menuSmall as $small)
            {

                if(is_array($small->getItems())) {

                    $template .= '<div class="col-sm-4">';

                    $subItems = $small->getItems();

                    $fistItem = end($subItems);
                    $label = $fistItem->getLabel();
                    $url = $fistItem->getUrl();
                    array_pop($subItems);

                    $template .= '<p><a href="' . $url . '">' . $label . '</a></p>';

                    if(!empty($subItems))
                    {
                        $template .= '<ul>';

                        foreach($subItems as $subItem) {
                            $subItemNode = $subItem->getItems();
                            $label = $subItemNode->getLabel();
                            $url = $subItemNode->getUrl();

                            $template .= '<li><a href="'.$url.'">'.$label.'</a></li>';
                        }

                        $template .= '</ul>';
                    }

                    $template .= '</div>';

                }

            }

            $template .= '</row>';
        }

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