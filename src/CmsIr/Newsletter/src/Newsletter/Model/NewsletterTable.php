<?php
namespace CmsIr\Newsletter\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NewsletterTable extends ModelTable implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
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

            $tmp[] = $this->getGroupsToDisplay($row->getGroups());
            $tmp[] = $this->getLabelToDisplay($row->getStatusId());

            $tmp[] = '<a href="users/preview/'.$row->getId().'" class="btn btn-info" data-toggle="tooltip" title="Podgląd"><i class="fa fa-eye"></i></a> ' .
                '<a href="users/edit/'.$row->getId().'" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' .
                '<a href="users/delete/'.$row->getId().'" id="'.$row->getId().'" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';
            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getLabelToDisplay ($labelValue)
    {
        $status = $this->getStatusTable()->getBy(array('id' => $labelValue));
        $currentStatus = reset($status);
        $currentStatus->getName() == 'Send' ? $checked = 'label-primary' : $checked = 'label-default';
        $currentStatus->getName() == 'Send' ? $name = 'Wysłana' : $name= 'Szkic';

        $template = '<span class="label ' . $checked . '">' .$name . '</span>';
        return $template;
    }

    public function getGroupsToDisplay ($groups)
    {
        $subscriberGroups = unserialize($groups);

        $template = '';
        foreach($subscriberGroups as $group) {
            $template .= '<span class="label label-info">' .$group . '</span> ';
        }

        return $template;
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