<?php
namespace CmsIr\Menu\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;

class MenuNodeTable extends ModelTable
{

    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getMenuNode($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $result = $this->getResultSetAsArrayObject($rowset);
        if (!$result) {
            throw new \Exception("Could not find row $id");
        }
        return $result;
    }

    public function deleteMenuNode($id)
    {
        $id  = (int) $id;
        $this->tableGateway->delete(array('id' => $id));
    }

    public function saveOrderNode($nodeId, $parentId = null, $position)
    {
        $data = array(
            'parent_id' => $parentId,
            'position' => $position
        );

        if ($nodeId) {
            $this->tableGateway->update($data, array('id' => $nodeId));
        } else {
            throw new \Exception('Node id does not exist');
        }

    }

    public function saveOrderWithoutChildren($nodeId, $parentId, $position)
    {
        $data = array(
            'parent_id' => null,
            'position' => $position
        );

        if ($nodeId) {
            $this->tableGateway->update($data, array('id' => $nodeId));
        } else {
            throw new \Exception('Node id does not exist');
        }

    }

    public function saveMenuNode(MenuNode $menuNode)
    {
        $data = array(
            'depth' => $menuNode->getDepth(),
            'is_visible'  => $menuNode->getIsVisible(),
            'provider_type'  => $menuNode->getProviderType(),
            'position'  => $menuNode->getPosition(),
            'parent_id'  => $menuNode->getParentId(),
            'tree_id'  => $menuNode->getTreeId(),
        );

        $id = (int) $menuNode->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else {
            if ($this->getMenuNode($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User id does not exist');
            }
        }
        return $id;
    }
}