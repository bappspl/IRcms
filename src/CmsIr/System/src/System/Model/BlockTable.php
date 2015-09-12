<?php

namespace CmsIr\System\Model;

use CmsIr\Post\Model\Post;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlockTable extends ModelTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function save(Block $block)
    {
        $data = array(
            'entity_id' => $block->getEntityId(),
            'entity_type'  => $block->getEntityType(),
            'language_id'  => $block->getLanguageId(),
            'value'  => $block->getValue(),
            'name'  => $block->getName(),
        );

        $id = (int) $block->getId();
        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else
        {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Block id does not exist');
            }
        }

        return $id;
    }
}