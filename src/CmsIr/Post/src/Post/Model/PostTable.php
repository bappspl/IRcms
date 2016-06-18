<?php
namespace CmsIr\Post\Model;

use CmsIr\System\Model\ModelTable;
use CmsIr\System\Util\Inflector;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;

class PostTable extends ModelTable implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getPost($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function deletePost($ids)
    {
        if(!is_array($ids)) {
            $ids = array($ids);
        }

        foreach($ids as $id) {
            $this->tableGateway->delete(array('id' => $id));
        }
    }

    public function changeStatusPost($ids, $statusId)
    {
        if(!is_array($ids)) {
            $ids = array($ids);
        }
        $data = array('status_id'  => $statusId);
        foreach($ids as $id) {
            $this->tableGateway->update($data, array('id' => $id));
        }
    }

    public function save(Post $post)
    {
        $data = array(
            'name' => $post->getName(),
            'status_id'  => $post->getStatusId(),
            'category'  => $post->getCategory(),
            'date'  => $post->getDate(),
            'author_id'  => $post->getAuthorId(),
            'filename_main'  => $post->getFilenameMain(),
            'extra'  => $post->getExtra(),
            'filename_background'  => $post->getFilenameBackground(),
            'slug' => Inflector::slugify($post->getName()),
            );

        $id = (int) $post->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } else {
            if ($this->getPost($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Post id does not exist');
            }
        }
        return $id;
    }

    public function getDataToDisplayMod ($filteredRows, $columns, $category)
    {
        $dataArray = array();
        foreach($filteredRows as $row) {

            $tmp = array();
            foreach($columns as $column){
                $column = 'get'.ucfirst($column);
                if($column == 'getStatus') {
                    $tmp[] = $this->getLabelToDisplay($row->getStatusId());
                } else {
                    $tmp[] = $row->$column();
                }
            }
            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getPostDatatables($columns, $data, $category, $userId = null)
    {
        $displayFlag = false;

        $allRows = $this->getBy(array('category' => $category));
        $countAllRows = count($allRows);

        $trueOffset = (int) $data->iDisplayStart;
        $trueLimit = (int) $data->iDisplayLength;

        $sorting = array('id', 'asc');
        if(isset($data->iSortCol_0)) {
            $sorting = $this->getSortingColumnDir($columns, $data);
        }

        $where = array();
        if ($data->sSearch != '') {
            $columnsToSearch = array('id', 'name', 'date', 'status_id');
            $where = array(
                new Predicate\PredicateSet(
                    $this->getFilterPredicate($columnsToSearch, $data),
                    Predicate\PredicateSet::COMBINED_BY_OR
                ),
            );
            $where['category'] = $category;
            $displayFlag = true;
        } else {
            $where['category'] = $category;
        }

        if($userId) $where['author_id'] = $userId;

        $filteredRows = $this->tableGateway->select(function(Select $select) use ($trueLimit, $trueOffset, $sorting, $where){
            $select
                ->where($where)
                ->order($sorting[0] . ' ' . $sorting[1])
                ->limit($trueLimit)
                ->offset($trueOffset);
        });

        $dataArray = $this->getDataToDisplayMod($filteredRows, $columns, $category);

        if($displayFlag == true) {
            $countFilteredRows = $filteredRows->count();
        } else {
            $countFilteredRows = $countAllRows;
        }

        return array('iTotalRecords' => $countAllRows, 'iTotalDisplayRecords' => $countFilteredRows, 'aaData' => $dataArray);
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

    public function getSearchWithPaginationBy($object, $where, $order = null)
    {
        $whereLike['status_id'] = $where['status_id'];
        $whereLike[] = new Predicate\PredicateSet(
            array(
                new Predicate\Like('name', '%'.$where['slug'].'%'),
                new Predicate\Like('text', '%'.$where['slug'].'%'),
            ),
            Predicate\PredicateSet::COMBINED_BY_OR
        );
        //$whereLike[] = new Predicate\Like('name', '%'.$where['slug'].'%');
        $select = $this->tableGateway->getSql()->select();
        $select->where($whereLike);

        if (!empty($order)) {
            $select->order($order);
        }
        $select->group(array('id'));

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype($object);
        $resultSetPrototype->buffer();

        $paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), $resultSetPrototype);
        $paginator = new Paginator($paginatorAdapter);

        return $paginator;
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