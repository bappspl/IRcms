<?php
namespace CmsIr\File\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;

class FileTable extends ModelTable
{
    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function save(File $file)
    {
        $data = array(
            'entity_id' => $file->getEntityId(),
            'entity_type' => $file->getEntityType(),
            'filename' => $file->getFileName(),
            'mime_type' => $file->getMimeType(),
        );

        $id = (int) $file->getId();

        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('File id does not exist');
            }
        }
    }

    public function deleteFile($id)
    {
        $id  = (int) $id;
        $this->tableGateway->delete(array('id' => $id));
    }

    public function deleteFilesWhere($where)
    {
        $this->tableGateway->delete($where);
    }
}