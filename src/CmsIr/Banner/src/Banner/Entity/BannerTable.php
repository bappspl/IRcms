<?php

namespace CmsIr\Banner\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

class BannerTable extends EntityRepository
{
    public function getDataToDisplay ($filteredRows, $columns)
    {
        $dataArray = array();
        foreach($filteredRows as $row) {
            $tmp = array();
            foreach($columns as $column) {
                $column = 'get'.ucfirst($column);
                if($column == 'getStatus') {
                    $tmp[] = $this->getLabelToDisplay($row->getStatus()->getName());
                } elseif ($column == 'getStatusId') {
                    $tmp[] = $this->getLabelToDisplay($row->getStatus()->getId());
                } else {
                    $tmp[] = $row->$column();
                }
            }

            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getLabelToDisplay ($labelValue)
    {
        $labelValue == 'Active' ? $checked = 'label-primary' : $checked = 'label-default';
        $labelValue == 'Active' ? $name = 'Aktywna' : $name  = 'Nieaktywna';

        $template = '<span class="label ' . $checked . '">' . $name . '</span>';
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

        $qb->select('banner');
        $qb->orderBy('banner.' . $sorting[0], $sorting[1]);
        $qb->setFirstResult($trueOffset);
        $qb->setMaxResults($trueLimit);
        $qb->from('CmsIr\Banner\Entity\Banner','banner');
        $qb->innerJoin('CmsIr\System\Entity\Status', 'status', 'WITH', 'banner.status = status.id');
        $qb->andWhere($qb->expr()->isNull('banner.removed'));

        if ($data->sSearch != '') {
            $orx = $qb->expr()->orX();

            for ($i = 0; $i < count($columns); $i++) {
                if ($columns[$i] != 'statusId' && $columns[$i] != 'status') {
                    $orx->add($qb->expr()->like('banner.' . $columns[$i], '?' . $i));
                    $qb->setParameter($i, '%'.$data->sSearch.'%');
                }
            }

            $qb->andWhere($orx);

            $displayFlag = true;
        }

        $filteredRows = $qb->getQuery()->getResult();

        $dataArray = $this->getDataToDisplay($filteredRows, $columns);

        if($displayFlag == true) {
            $countFilteredRows = count($filteredRows);
        } else {
            $countFilteredRows = $countAllRows;
        }

        return array('iTotalRecords' => $countAllRows, 'iTotalDisplayRecords' => $countFilteredRows, 'aaData' => $dataArray);
    }

    public function getSortingColumnDir ($columns, $data)
    {
        for ($i=0 ; $i<intval($data->iSortingCols); $i++) {
            if ($data['bSortable_'.intval($data['iSortCol_'.$i])] == 'true') {
                $sortingColumn = $columns[$data['iSortCol_'.$i]];

                if(preg_match_all("/[A-Z]/", $sortingColumn, $matches) !== 0) {
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
        $qb->select('count(banner.id)');
        $qb->from('CmsIr\Banner\Entity\Banner','banner');
        $qb->where($qb->expr()->isNull('banner.removed'));
        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }
}