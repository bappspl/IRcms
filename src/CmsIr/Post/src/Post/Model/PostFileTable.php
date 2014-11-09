<?php
namespace CmsIr\Post\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PostFileTable extends ModelTable implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getPostFile($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function deletePostFile($id)
    {
        $id  = (int) $id;
        $this->tableGateway->delete(array('id' => $id));
    }

    public function save(PostFile $file)
    {
        $data = array(
            'filename' => $file->getFilename(),
            'post_id'  => $file->getPostId(),
            'size'  => $file->getSize()
        );

        $id = (int) $file->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPost($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('File id does not exist');
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