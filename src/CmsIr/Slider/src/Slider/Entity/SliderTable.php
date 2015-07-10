<?php

namespace CmsIr\Slider\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr\Join;

class SliderTable extends EntityRepository
{
    public function getDataToDisplay ($filteredRows, $columns)
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

            $tmp[] = $this->getLabelToDisplay($row->getStatus()->getName());

            $tmp[] = '<a href="slider/edit/'.$row->getId().'" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' .
                '<a href="slider/delete/'.$row->getId().'" id="'.$row->getId().'" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';
            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getLabelToDisplay ($labelValue)
    {
        $labelValue == 'Active' ? $checked = 'label-primary' : $checked = 'label-default';
        $labelValue == 'Active' ? $name = 'Aktywna' : $name= 'Nieaktywna';

        $template = '<span class="label ' . $checked . '">' .$name . '</span>';
        return $template;
    }

    public function getDatatables($columns, $data)
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

        $qb->select('slider');
        $qb->orderBy('slider.' . $sorting[0], $sorting[1]);
        $qb->setFirstResult($trueOffset);
        $qb->setMaxResults($trueLimit);
        $qb->from('CmsIr\Slider\Entity\Slider','slider');
        $qb->innerJoin('CmsIr\System\Entity\Status', 'status', 'WITH', 'slider.status = status.id');

        if ($data->sSearch != '')
        {
            for ( $i=0 ; $i<count($columns) ; $i++ )
            {
                $qb->orWhere($qb->expr()->like('slider.' . $columns[$i], '?' . $i));
                $qb->setParameter($i, '%'.$data->sSearch.'%');
            }

            $displayFlag = true;
        }

        $filteredRows = $qb->getQuery()->getResult();

        $dataArray = $this->getDataToDisplay($filteredRows, $columns);

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
        $qb->select('count(slider.id)');
        $qb->from('CmsIr\Slider\Entity\Slider','slider');
        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }
}