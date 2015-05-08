<?php
namespace CmsIr\Menu\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;

class MenuItemTable extends ModelTable
{

    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getMenuItem($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $result = $this->getResultSetAsArrayObject($rowset);
        if (!$result) {
            throw new \Exception("Could not find row $id");
        }
        return $result;
    }

    public function deleteMenuItemByNodeId($nodeid)
    {
        $id  = (int) $nodeid;
        $this->tableGateway->delete(array('node_id' => $id));
    }

    public function updateMenuItem($nodeid, $label, $url, $subtitle)
    {
        $id  = (int) $nodeid;
        $data = array(
            'label' => $label,
            'url' => $url,
            'subtitle' => $subtitle
        );
        $this->tableGateway->update($data, array('node_id' => $id));
    }

    public function saveMenuItem(MenuItem $menuItem)
    {
        $data = array(
            'label' => $menuItem->getLabel(),
            'url'  => $menuItem->getUrl(),
            'subtitle' => $menuItem->getSubtitle(),
            'node_id'  => $menuItem->getNodeId()
        );

        $id = (int) $menuItem->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getMenuItem($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User id does not exist');
            }
        }
    }
}