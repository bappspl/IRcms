<?php

namespace CmsIr\System\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Predicate\IsNull;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\AdapterAwareInterface;

class MenuTable extends AbstractTableGateway  implements AdapterAwareInterface
{
    protected $table = 'cms_backend_menu';

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new HydratingResultSet();

        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select(function (Select $select){
            $select->order(array('id asc'));
            $select->where(new IsNull('parent_id'));
        });

        $resultSet = $resultSet->toArray();

        return $resultSet;
    }

    public function getByParentId($parentid)
    {
        $resultSet = $this->select(function (Select $select) use ($parentid){
            $select->order(array('id asc'));
            $select->where(array('parent_id' => $parentid));
        });

        $resultSet = $resultSet->toArray();

        return $resultSet;
    }

    public function save($data)
    {
        $this->insert($data);
    }
}