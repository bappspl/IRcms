<?php

namespace CmsIr\System\Model;

use Zend\Db\TableGateway\TableGateway;


class MailConfigTable extends ModelTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function generateMailConfigArray()
    {
        /* @var $config MailConfig */
        $config = $this->getOneBy(array('id' => 1));

        $options = array(
            'host'              => $config->getHost(),
            'connection_class'  => 'plain',
            'connection_config' => array(
                'username' => $config->getUsername(),
                'password' => $config->getPassword(),
            ),
        );

        return $options;
    }

    public function save(MailConfig $config)
    {
        $data = array(
            'host' => $config->getHost(),
            'username'  => $config->getUsername(),
            'password'  => $config->getPassword(),
            'send'  => $config->getSend(),
        );

        $id = (int) $config->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Mail config id does not exist');
            }
        }
    }

}