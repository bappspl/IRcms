<?php
namespace CmsIr\Slider\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SliderItemTable extends ModelTable
{

    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function deleteSliderItem($id)
    {
        $id  = (int) $id;
        $this->tableGateway->delete(array('id' => $id));
    }

    public function save(SliderItem $slider)
    {
        $data = array(
            'name' => $slider->getName(),
            'slider_id'  => $slider->getSliderId(),
            'status_id'  => $slider->getStatusId(),
            'filename'  => $slider->getFilename(),
            'position'  => $slider->getPosition(),
        );

        $id = (int) $slider->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Slider item id does not exist');
            }
        }
    }

    public function saveItems($data)
    {
        $i = 0;
        foreach($data as $sliderItem)
        {
            $id = $sliderItem['id'];
            if ($id) {

                $dataItem = array('position' => $i);
                $this->tableGateway->update($dataItem, array('id' => $id));
            } else {
                throw new \Exception('Item id does not exist');
            }

            $i++;
        }
    }
}