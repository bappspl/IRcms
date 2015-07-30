<?php
namespace CmsIr\Meta\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate;

class MetaTable extends ModelTable
{
    protected $serviceLocator;
    protected $tableGateway;
    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function save(Meta $meta)
    {
        $data = array(
            'title' => $meta->getTitle(),
            'keywords' => $meta->getKeywords(),
            'description' => $meta->getDescription(),
            'entity_id' => $meta->getEntityId(),
            'entity_type' => $meta->getEntityType()
        );

        $id = (int) $meta->getId();
        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else
        {
            if ($this->getOneBy(array('id' => $id)))
            {
                $this->tableGateway->update($data, array('id' => $id));
            } else
            {
                throw new \Exception('Banner id does not exist');
            }
        }

        return $id;
    }

    public function deleteMeta($entityId, $entityType)
    {
        $id  = (int) $entityId;
        $this->tableGateway->delete(array('entity_id' => $entityId, 'entity_type' => $entityType));
    }
}