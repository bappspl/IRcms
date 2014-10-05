<?php
namespace CmsIr\Slider\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SliderTable extends ModelTable implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getSlider($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $result = $this->getResultSetAsArrayObject($rowset);
        if (!$result) {
            throw new \Exception("Could not find row $id");
        }
        return $result;
    }

    public function deleteSlider($id)
    {
        $id  = (int) $id;
        $this->tableGateway->delete(array('id' => $id));
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
            // dodanie switchera
            $tmp[] = $this->getLabelToDisplay($row->getStatusId());

            $tmp[] = '<a href="slider/items/'.$row->getId().'" class="btn btn-info" data-toggle="tooltip" title="Lista"><i class="fa fa-list"></i></a> ' .
                     '<a href="slider/edit/'.$row->getId().'" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' .
                     '<a href="slider/delete/'.$row->getId().'" id="'.$row->getId().'" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';
            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getLabelToDisplay ($labelValue)
    {
        $status = $this->getStatusTable()->getBy(array('id' => $labelValue));
        $currentStatus = reset($status);
        $currentStatus->getName() == 'Active' ? $checked = 'label-primary' : $checked = 'label-default';
        $currentStatus->getName() == 'Active' ? $name = 'Aktywny' : $name= 'Nieaktywny';

        $template = '<span class="label ' . $checked . '">' .$name . '</span>';
        return $template;
    }

    public function save(Slider $slider)
    {
        $data = array(
            'name' => $slider->getName(),
            'slug'  => $slider->getSlug(),
            'status_id'  => $slider->getStatusId(),
        );

        $id = (int) $slider->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Slider id does not exist');
            }
        }
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}