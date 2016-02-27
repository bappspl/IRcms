<?php
namespace CmsIr\System\Navigation;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;

class MyNavigation extends DefaultNavigationFactory
{
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {
            //FETCH data from table menu :
            $fetchMenu = $serviceLocator->get('menu')->fetchAll();

            foreach($fetchMenu as $key => $row) {
                $configuration['navigation'][$this->getName()][$row['name']] = array(
                    'label' => $row['label'],
                    'route' => $row['route'],
                    'class' => $row['class'],
                    'id' => $row['access'],
                    'visibleInPrimary' => $row['visible_in_primary'],
                );

                $pages = $serviceLocator->get('menu')->getByParentId($row['id']);

                $pagesArray = array();

                if(!empty($pages)) {
                    foreach($pages as $k => $page) {
                        $params = null;

                        if(!empty($page['params'])) {
                            $params = substr($page['params'], 1, strlen($page['params']) - 2);
                            $string = explode(' => ', $params);
                            $k = rtrim($string[0], "'");
                            $value = ltrim($string[1], "'");
                        }

                        $child = array(
                          'label' => $page['label'],
                          'route' => $page['route'],
                          'visibleInPrimary' => $page['visible_in_primary'],
                          'params' => $params ? array($k => $value) : array(),
                        );

                        array_push($pagesArray, $child);
                    }

                    $configuration['navigation'][$this->getName()][$row['name']]['pages'] = $pagesArray;
                    unset($pagesArray);
                }

            }

            if (!isset($configuration['navigation'])) {
                throw new \Exception('Could not find navigation configuration key');
            }

            $application = $serviceLocator->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();
            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);

            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }
        return $this->pages;
    }
}