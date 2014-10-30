<?php
namespace CmsIr\Newsletter\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;

class SubscriberGroupTable extends ModelTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function deleteSubscriberGroup($id)
    {
        $id  = (int) $id;
        $this->tableGateway->delete(array('id' => $id));
    }

    public function save(SubscriberGroup $subscriberGroup)
    {
        $data = array(
            'name' => $subscriberGroup->getName(),
            'slug'  => $subscriberGroup->getSlug(),
            'description'  => $subscriberGroup->getDescription(),
        );

        $id = (int) $subscriberGroup->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('SubscriberGroup id does not exist');
            }
        }
    }

    public function getDataToDisplay ($filteredRows, $columns)
    {
        $dataArray = array();
        foreach($filteredRows as $row) {

            $tmp = array();
            foreach($columns as $column){
                $column = 'get'.ucfirst($column);
                $tmp[] = $row->$column();
            }

            $tmp[] = '<a href="subscriber-group/preview-group/'.$row->getId().'" class="btn btn-info" data-toggle="tooltip" title="Podgląd"><i class="fa fa-eye"></i></a> ' .
                '<a href="subscriber-group/edit-group/'.$row->getId().'" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' .
                '<a href="subscriber-group/delete-group/'.$row->getId().'" id="'.$row->getId().'" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';
            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }
}