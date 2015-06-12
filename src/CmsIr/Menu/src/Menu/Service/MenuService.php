<?php

namespace CmsIr\Menu\Service;

use CmsIr\Menu\Model\MenuItem;
use CmsIr\Menu\Model\MenuNode;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Parameters;

class MenuService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function getMenuByMachineName($machineName)
    {
        $menu = $this->getMenuTable()->getOneBy(array('machine_name' => $machineName));
        return $this->getMenuByTreeId($menu->getId());
    }

    public function getMenuByTreeId($treeId)
    {
        $menuNodes = $this->getMenuNodeTable()->getBy(array('tree_id' => $treeId), array('position' => 'ASC'));

        $nodesArray = array();
        foreach($menuNodes as $node) {

            /* @var $node MenuNode */
            /* @var $nodesItem MenuItem */
            $nodeId = $node->getId();
            $parentId = $node->getParentId();
            if(is_null($parentId))
            {
                $nodesNode = $this->getMenuNodeTable()->getBy(array('parent_id' => $nodeId), array('position' => 'ASC'));
                if(empty($nodesNode)) {
                    $nodesItem = $this->getMenuItemTable()->getOneBy(array('node_id' => $nodeId));
                    $node->setItems($nodesItem);
                } else {
                    foreach($nodesNode as $nodeNode)
                    {
                        /* @var $nodeNode MenuNode */
                        $nodeNodeId = $nodeNode->getId();
                        $nodesItem = $this->getMenuItemTable()->getOneBy(array('node_id' => $nodeNodeId));
                        $nodeNode->setItems($nodesItem);
                    }
                    $nodesNode[] = $this->getMenuItemTable()->getOneBy(array('id' => $nodeId));
                    $node->setItems($nodesNode);

                }
                $nodesArray[] = $node;
            }
        }

        return $nodesArray;
    }

    public function saveMenu($data)
    {
        $i = 0;
        foreach($data as $menuItem)
        {
            if(array_key_exists('children', $menuItem))
            {
                $a = 0;
                foreach($menuItem['children'] as $child)
                {
                    $id = $child['id'];
                    $this->getMenuNodeTable()->saveOrderNode($id, $menuItem['id'], $a);
                    $a++;
                }
                $id = $menuItem['id'];
                $this->getMenuNodeTable()->saveOrderNode($id, null, $i);
            } else {
                $id = $menuItem['id'];
                $this->getMenuNodeTable()->saveOrderNode($id, null, $i);
            }
            $i++;
        }
    }

    public function findMenuItemsForPage()
    {
        $nodes = $this->getMenuNodeTable()->getBy(array('depth' => 0));

        /* @var $node MenuNode */
        foreach($nodes as $node)
        {
            $node->setItems($this->getMenuItemTable()->getOneBy(array('node_id' => $node->getId())));
        }

        return $nodes;
    }

    public function saveMenuNode(MenuNode $menuNode)
    {
        $id = $this->getMenuNodeTable()->saveMenuNode($menuNode);

        return $id;
    }

    public function saveMenuItem(MenuItem $menuItem)
    {
        $this->getMenuItemTable()->saveMenuItem($menuItem);
    }

    /**
     * @return \CmsIr\Menu\Model\MenuTable
     */
    public function getMenuTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Menu\Model\MenuTable');
    }
    /**
     * @return \CmsIr\Menu\Model\MenuNodeTable
     */
    public function getMenuNodeTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Menu\Model\MenuNodeTable');
    }
    /**
     * @return \CmsIr\Menu\Model\MenuItemTable
     */
    public function getMenuItemTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Menu\Model\MenuItemTable');
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
