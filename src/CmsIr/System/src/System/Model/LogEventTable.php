<?php
namespace CmsIr\System\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LogEventTable extends ModelTable implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function changeStatusLogEvent($ids, $statusId)
    {
        if(!is_array($ids))
        {
            $ids = array($ids);
        }
        $data = array('viewed'  => $statusId);
        foreach($ids as $id)
        {
            $this->tableGateway->update($data, array('id' => $id));
        }
    }

    // datatables

    public function getDatatables($columns, $data)
    {
        $displayFlag = false;

        $allRows = $this->getAll();
        $countAllRows = count($allRows);

        $trueOffset = (int) $data->iDisplayStart;
        $trueLimit = (int) $data->iDisplayLength;

        $sorting = array('id', 'asc');
        if(isset($data->iSortCol_0)) {
            $sorting = $this->getSortingColumnDir($columns, $data);
        }

        $where = array();
        if ($data->sSearch != '') {
            $where = array(
                new Predicate\PredicateSet(
                    $this->getFilterPredicate($columns, $data),
                    Predicate\PredicateSet::COMBINED_BY_OR
                )
            );
            $displayFlag = true;
        }

        $filteredRows = $this->tableGateway->select(function(Select $select) use ($trueLimit, $trueOffset, $sorting, $where){
            $select
                ->where($where)
                ->order($sorting[0] . ' ' . $sorting[1])
                ->limit($trueLimit)
                ->offset($trueOffset);
        });

        $dataArray = array();
        foreach($filteredRows as $row)
        {
            $user = $row->getUser();
            $username = explode('@', $user);
            $name = $username[0];

            $cmsUser = $this->getUsersTable()->findByName($name);

            $row->setUser($cmsUser->getName() . ' ' . $cmsUser->getSurname());

            $tmp = array(
                $row->getId(),
                $row->getEntityType(),
                $row->getUser(),
                $row->getWhat(),
                $row->getAction(),
                $row->getDescription(),
                $row->getDate(),
                $this->getLabelToDisplay($row->getViewed())
            );

            array_push($dataArray, $tmp);
        }

        if($displayFlag == true) {
            $countFilteredRows = $filteredRows->count();
        } else {
            $countFilteredRows = $countAllRows;
        }

        return array('iTotalRecords' => $countAllRows, 'iTotalDisplayRecords' => $countFilteredRows, 'aaData' => $dataArray);
    }

    public function getLabelToDisplay ($labelValue)
    {
        $labelValue == 1 ? $checked = 'label-primary' : $checked = 'label-default';
        $labelValue == 1 ? $name = 'Przeczytane' : $name = 'Nieprzeczytane';

        $template = '<span class="label ' . $checked . '">' .$name . '</span>';
        return $template;
    }

    /**
     * @return \CmsIr\Users\Model\UsersTable
     */
    public function getUsersTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Users\Model\UsersTable');
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