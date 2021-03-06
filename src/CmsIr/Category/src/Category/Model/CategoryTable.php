<?php
namespace CmsIr\Category\Model;

use CmsIr\System\Model\ModelTable;
use CmsIr\System\Util\Inflector;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;

class CategoryTable extends ModelTable
{

    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getCategory($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $result = $this->getResultSetAsArrayObject($rowset);
        if (!$result) {
            throw new \Exception("Could not find row $id");
        }
        return $result;
    }

    public function deleteCategory($ids)
    {
        if(!is_array($ids)) {
            $ids = array($ids);
        }

        foreach($ids as $id) {
            $this->tableGateway->delete(array('id' => $id));
        }
    }

    public function getDataToDisplay ($filteredRows, $columns)
    {
        $dataArray = array();
        foreach($filteredRows as $row) {
            $tmp = array();
            foreach($columns as $column) {
                $column = 'get'.ucfirst($column);
                $tmp[] = $row->$column();
            }

            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getCategoryDatatables($columns, $data, $type)
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
            $columnsToSearch = array('id', 'name');
            $where = array(
                new Predicate\PredicateSet(
                    $this->getFilterPredicate($columnsToSearch, $data),
                    Predicate\PredicateSet::COMBINED_BY_OR
                ),
            );
            $where['type'] = $type;
            $displayFlag = true;
        } else {
            $where['type'] = $type;
        }

        $filteredRows = $this->tableGateway->select(function(Select $select) use ($trueLimit, $trueOffset, $sorting, $where){
            $select
                ->where($where)
                ->order($sorting[0] . ' ' . $sorting[1])
                ->limit($trueLimit)
                ->offset($trueOffset);
        });

        $dataArray = $this->getDataToDisplay($filteredRows, $columns);

        if($displayFlag == true) {
            $countFilteredRows = $filteredRows->count();
        } else {
            $countFilteredRows = $countAllRows;
        }

        return array('iTotalRecords' => $countAllRows, 'iTotalDisplayRecords' => $countFilteredRows, 'aaData' => $dataArray);
    }

    public function save(Category $category)
    {
        $data = array(
            'name' => $category->getName(),
            'filename_main' => $category->getFilenameMain(),
            'type' => $category->getType(),
        );

        $id = (int) $category->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;

            $pos = array('position' => $id);

            $this->tableGateway->update($pos, array('id' => $id));
        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Dictionary id does not exist');
            }
        }

        return $id;
    }
}