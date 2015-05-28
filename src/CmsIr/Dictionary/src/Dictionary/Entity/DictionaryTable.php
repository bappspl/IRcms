<?php

namespace CmsIr\Dictionary\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr\Join;

class DictionaryTable extends EntityRepository
{
    public function getDataToDisplay ($filteredRows, $columns, $category)
    {
        $dataArray = array();
        foreach($filteredRows as $row)
        {

            $tmp = array();
            foreach($columns as $column)
            {
                $column = 'get'.ucfirst($column);
                $tmp[] = $row->$column();
            }

            $tmp[] = '<a href="'.$category.'/edit/'.$row->getId().'" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' .
                '<a href="'.$category.'/delete/'.$row->getId().'" id="'.$row->getId().'" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';
            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getDatatables($columns, $data, $category)
    {
        $displayFlag = false;

        $countAllRows = $this->countRows();

        $trueOffset = (int) $data->iDisplayStart;
        $trueLimit = (int) $data->iDisplayLength;

        $sorting = array('id', 'asc');
        if(isset($data->iSortCol_0)) {
            $sorting = $this->getSortingColumnDir($columns, $data);
        }

        $qb = $this->_em->createQueryBuilder();

        $qb->select('d');
        $qb->orderBy('d.' . $sorting[0], $sorting[1]);
        $qb->setFirstResult($trueOffset);
        $qb->setMaxResults($trueLimit);
        $qb->from('CmsIr\Dictionary\Entity\Dictionary','d');

        if ($data->sSearch != '')
        {
            for ( $i=0 ; $i<count($columns) ; $i++ )
            {
                $qb->orWhere($qb->expr()->like('d.' . $columns[$i], '?' . $i));
                $qb->setParameter($i, '%'.$data->sSearch.'%');
            }

            $displayFlag = true;
        }

        $filteredRows = $qb->getQuery()->getResult();

        $dataArray = $this->getDataToDisplay($filteredRows, $columns, $category);

        if($displayFlag == true)
        {
            $countFilteredRows = count($filteredRows);
        } else
        {
            $countFilteredRows = $countAllRows;
        }

        return array('iTotalRecords' => $countAllRows, 'iTotalDisplayRecords' => $countFilteredRows, 'aaData' => $dataArray);
    }

    public function getSortingColumnDir ($columns, $data)
    {
        for ($i=0 ; $i<intval($data->iSortingCols); $i++)
        {
            if ($data['bSortable_'.intval($data['iSortCol_'.$i])] == 'true')
            {
                $sortingColumn = $columns[$data['iSortCol_'.$i]];
                if(preg_match_all("/[A-Z]/", $sortingColumn, $matches) !== 0)
                {
                    $sortingColumn = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $sortingColumn));
                }
                $sortingDir = $data['sSortDir_'.$i];
                return array($sortingColumn, $sortingDir);
            }
        }
        return array();
    }

    public function countRows()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(d.id)');
        $qb->from('CmsIr\Dictionary\Entity\Dictionary','d');
        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }
}