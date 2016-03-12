<?php

namespace CmsIr\Banner\Service;

use CmsIr\Banner\Entity\Banner;
use CmsIr\File\Model\File;
use CmsIr\System\Entity\EntityType;
use CmsIr\System\Entity\Status;
use CmsIr\System\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BannerService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    private $em;
    private $uploadDir = 'public/files/banner/';

    /**
     * FileService constructor.
     * @param $em
     */
    public function __construct($em)
    {
        /* @var $em EntityManager */
        $this->em = $em;
    }

    /*
     * BACK
     */

    public function getDataTables($data)
    {
        $columns = array('id', 'name', 'statusId', 'status', 'position', 'id');
        $listData = $this->em->getRepository(\CmsIr\Banner\Entity\Banner::ENTITY)->getDatatables($columns, $data);

        $output = array(
            "Echo" => $data['Echo'],
            "iTotalRecords" => $listData['iTotalRecords'],
            "iTotalDisplayRecords" => $listData['iTotalDisplayRecords'],
            "aaData" => $listData['aaData']
        );

        return $output;
    }

    public function createBanner($data)
    {
        $entityType = $this->em->getRepository(EntityType::ENTITY)->findOneBy(array('name' => 'banner'));
        $status = $this->em->getReference(Status::ENTITY, $data['status_id']);

        $banner = new Banner();
        $banner->exchangeArray($data);
        $banner->setSlug(Inflector::slugify($banner->getName()));
        $banner->setDateCreating(new \DateTime());
        $banner->setEntityType($entityType);
        $banner->setStatus($status);

        $this->em->persist($banner);
        $this->em->flush();

        $banner->setPosition($banner->getId());

        $this->em->merge($banner);
        $this->em->flush();
    }

    public function getBanner($id)
    {
        $banner = $this->em->getRepository(Banner::ENTITY)->find($id);

        return $banner;
    }

    public function editBanner($banner)
    {
        /* @var $banner Banner */
        $status = $this->em->getReference(Status::ENTITY, $banner->getStatusId());

        $banner->setSlug(Inflector::slugify($banner->getName()));
        $banner->setDateEditing(new \DateTime());
        $banner->setStatus($status);

        $this->em->merge($banner);
        $this->em->flush();
    }

    public function deleteBanner($ids)
    {
        foreach ($ids as $id) {
            /* @var $banner Banner */
            $banner = $this->em->getReference(Banner::ENTITY, $id);

            $banner->setDateRemoving(new \DateTime());
            $banner->setRemoved(1);

            $this->em->merge($banner);
            $this->em->flush();
        }
    }

    public function changeStatus($data)
    {
        $ids = $data['id'];
        $statusId = $data['statusId'];

        if(!is_array($ids)) {
            $ids = array($ids);
        }

        $status = $this->em->getReference(Status::ENTITY, $statusId);

        foreach ($ids as $id) {
            /* @var $banner Banner */
            $banner = $this->em->getReference(Banner::ENTITY, $id);
            $banner->setStatus($status);

            $this->em->merge($banner);
            $this->em->flush();
        }
    }

    public function uploadFiles($files)
    {
        $tempFile   = $files['Filedata']['tmp_name'];
        $targetFile = $files['Filedata']['name'];

        $file = explode('.', $targetFile);
        $fileName = $file[0];
        $fileExt = $file[1];

        $uniqidFilename = $fileName.'-'.uniqid();
        $targetFile = $uniqidFilename.'.'.$fileExt;

        if(move_uploaded_file($tempFile,$this->uploadDir.$targetFile)) {
            return $targetFile;
        } else {
            return 0;
        }
    }

    public function changePosition($position)
    {
        foreach($position as $id => $pos) {
            if($pos > 0) {
                /* @var $banner Banner */
                $banner = $this->em->getReference(Banner::ENTITY, $id);
                $banner->setPosition($pos);

                $this->em->merge($banner);
                $this->em->flush();
            }
        }
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
