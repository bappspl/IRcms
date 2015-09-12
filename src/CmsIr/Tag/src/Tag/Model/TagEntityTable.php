<?php
namespace CmsIr\Tag\Model;

use CmsIr\System\Model\ModelTable;
use CmsIr\System\Util\Inflector;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;

class TagEntityTable extends ModelTable
{

    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function save(TagEntity $tagEntity)
    {
        $data = array(
            'entity_id' => $tagEntity->getEntityId(),
            'entity_type' => $tagEntity->getEntityType(),
            'tag_id' => $tagEntity->getTagId(),
        );

        $id = (int) $tagEntity->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('TagEntity id does not exist');
            }
        }

        return $id;
    }

    public function deleteTagEntity($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}