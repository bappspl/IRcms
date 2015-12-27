<?php
namespace CmsIr\System\Model;

use CmsIr\System\Util\Inflector;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;

class SettingsTable extends ModelTable
{
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function save(Settings $settings)
    {
        $data = array(
            'entity_id' => $settings->getEntityId(),
            'entity_type'  => $settings->getEntityType(),
            'name'  => $settings->getName(),
            'slug' => Inflector::slugify($settings->getName()),
            'option'  => $settings->getOption(),
            'set'  => $settings->getSet()
        );

        $id = (int) $settings->getId();

        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Settings id does not exist');
            }
        }

        return $id;
    }
}