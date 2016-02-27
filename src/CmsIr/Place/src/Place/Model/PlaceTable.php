<?php

namespace CmsIr\Place\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;

class PlaceTable extends ModelTable
{
    protected $serviceLocator;
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getPlace($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function deletePlace($ids)
    {
        if(!is_array($ids)) {
            $ids = array($ids);
        }

        foreach($ids as $id) {
            $this->tableGateway->delete(array('id' => $id));
        }
    }

    public function save(Place $place)
    {
        $data = array(
            'name' => $place->getName(),
            'latitude'  => $place->getLatitude(),
            'longitude'  => $place->getLongitude(),
            'country'  => $place->getCountry(),
            'region'  => $place->getRegion(),
            'city'  => $place->getCity(),
            'street'  => $place->getStreet(),
            'street_number'  => $place->getStreetNumber()
        );

        $id = (int) $place->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;

            $pos = array('position' => $id);

            $this->tableGateway->update($pos, array('id' => $id));
        } else {
            if ($this->getPlace($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Post id does not exist');
            }
        }
        return $id;
    }
}