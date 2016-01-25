<?php
namespace CmsIr\Menu\Controller;

use CmsIr\Menu\Model\Menu;
use CmsIr\Menu\Model\MenuItem;
use CmsIr\Menu\Model\MenuNode;
use CmsIr\System\Model\Status;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;


class MenuController extends AbstractActionController
{
    public function menuListAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('id', 'name', 'machineName', 'id');

            $listData = $this->getMenuService()->getMenuTable()->getDatatables($columns,$data);

            $output = array(
                "sEcho" => $this->getRequest()->getPost('sEcho'),
                "iTotalRecords" => $listData['iTotalRecords'],
                "iTotalDisplayRecords" => $listData['iTotalDisplayRecords'],
                "aaData" => $listData['aaData']
            );

            $jsonObject = Json::encode($output, true);
            echo $jsonObject;
            return $this->response;
        }

        return new ViewModel();
    }

    public function editAction()
    {
        $treeId = $this->params()->fromRoute('id');
        $menuTree = $this->getMenuService()->getMenuTable()->getOneBy(array('id' => $treeId));
        $menu = $this->getMenuService()->getMenuByTreeId($treeId);

        /* @var $activeStatus Status */

//        $existingPages = $this->getPageService()->findAllActiveWithBlocksByLanguageUrlShortcut('pl');
        $existingPages = $this->getPageTable()->getBy(array('status_id' => 1));

        $viewParams = array();
        $viewParams['menu'] = $menu;
        $viewParams['menuTree'] = $menuTree;
        $viewParams['treeId'] = $treeId;
        $viewParams['existingPages'] = $existingPages;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function orderAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost('data');
            $this->getMenuService()->saveMenu($data);

            $jsonObject = Json::encode($params['status'] = 'success', true);
            echo $jsonObject;
            return $this->response;
        }
        return $this->response;
    }

    public function deleteNodeAction ()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $nodes = $request->getPost('nodes');

            foreach($nodes as $node) {
                $this->getMenuService()->getMenuItemTable()->deleteMenuItemByNodeId($node);
                $this->getMenuService()->getMenuNodeTable()->deleteMenuNode($node);
            }

            $jsonObject = Json::encode($params['status'] = 'success', true);
            echo $jsonObject;
            return $this->response;
        }
        return $this->response;
    }

    public function editNodeAction ()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $nodeId = $request->getPost('nodeId');
            $nodeLabel = $request->getPost('label');
            $nodeUrl = $request->getPost('url');
            $subtitle = $request->getPost('subtitle');

            $nodeExist = $this->getMenuService()->getMenuItemTable()->getOneBy(array('url' => $nodeUrl));
            if($nodeExist == false) {
                // update
                $this->getMenuService()->getMenuItemTable()->updateMenuItem($nodeId, $nodeLabel, $nodeUrl, $subtitle);
                $jsonObject = Json::encode($params['status'] = 'success', true);
                echo $jsonObject;
                return $this->response;
            } else {

                if($nodeId == $nodeExist->getId()) {
                    // update
                    $this->getMenuService()->getMenuItemTable()->updateMenuItem($nodeId, $nodeLabel, $nodeUrl, $subtitle);
                    $jsonObject = Json::encode($params['status'] = 'success', true);
                    echo $jsonObject;
                    return $this->response;
                } else {
                    $jsonObject = Json::encode($params['status'] = 'error', true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

        }
        return $this->response;
    }

    public function createNodeAction ()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $nodeLabel = $request->getPost('label');
            $nodeUrl = $request->getPost('url');
            $subtitle = $request->getPost('subtitle');
            $treeId = $request->getPost('treeId');
            $pageProvider = $request->getPost('pageProvider');

            $nodeExist = $this->getMenuService()->getMenuItemTable()->getOneBy(array('url' => $nodeUrl));

            if($nodeExist == false) {
                // save
                $menuNode = new MenuNode();
                $menuNode->setDepth(0);
                $menuNode->setIsVisible(1);
                $menuNode->setProviderType($pageProvider);
                $menuNode->setPosition(0);
                $menuNode->setParentId(null);
                $menuNode->setTreeId($treeId);
                $nodeId = $this->getMenuService()->getMenuNodeTable()->saveMenuNode($menuNode);

                $menuItem = new MenuItem();
                $menuItem->setLabel($nodeLabel);
                $menuItem->setUrl($nodeUrl);
                $menuItem->setNodeId($nodeId);
                $menuItem->setSubtitle($subtitle);
                $menuItem->setPosition(0);
                $this->getMenuService()->getMenuItemTable()->saveMenuItem($menuItem);

                $jsonObject = Json::encode($params['status'] = 'success', true);
                echo $jsonObject;
                return $this->response;
            } else {
                $jsonObject = Json::encode($params['status'] = 'error', true);
                echo $jsonObject;
                return $this->response;
            }

        }
        return $this->response;
    }

    /**
     * @return \CmsIr\Menu\Service\MenuService
     */
    public function getMenuService()
    {
        return $this->getServiceLocator()->get('CmsIr\Menu\Service\MenuService');
    }

    /**
     * @return \CmsIr\Page\Model\PageTable
     */
    public function getPageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Model\PageTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\Page\Service\PageService
     */
    public function getPageService()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Service\PageService');
    }

}