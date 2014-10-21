<?php
namespace CmsIr\Newsletter\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NewsletterTable extends ModelTable implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function deleteNewsletter($id)
    {
        $id  = (int) $id;
        $this->tableGateway->delete(array('id' => $id));
    }

    public function save(Newsletter $newsletter)
    {
        $data = array(
            'subject' => $newsletter->getSubject(),
            'status_id'  => $newsletter->getStatusId(),
            'groups'  => serialize($newsletter->getGroups()),
            'text'  => $newsletter->getText(),
        );

        $id = (int) $newsletter->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Newsletter id does not exist');
            }
        }
    }

    public function getDataToDisplay ($filteredRows, $columns)
    {
        $dataArray = array();
        foreach($filteredRows as $row) {

            $tmp = array();
            foreach($columns as $column){
                $column = 'get'.ucfirst($column);
                $tmp[] = $row->$column();
            }

            $tmp[] = $this->getGroupsToDisplay($row->getGroups());
            $tmp[] = $this->getLabelToDisplay($row->getStatusId());

            $tmp[] = '<a href="users/preview/'.$row->getId().'" class="btn btn-info" data-toggle="tooltip" title="Podgląd"><i class="fa fa-eye"></i></a> ' .
                '<a href="newsletter/edit-newsletter/'.$row->getId().'" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' .
                '<a href="newsletter/delete-newsletter/'.$row->getId().'" id="'.$row->getId().'" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';
            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getLabelToDisplay ($labelValue)
    {
        $status = $this->getStatusTable()->getBy(array('id' => $labelValue));
        $currentStatus = reset($status);
        $currentStatus->getName() == 'Send' ? $checked = 'label-primary' : $checked = 'label-default';
        $currentStatus->getName() == 'Send' ? $name = 'Wysłana' : $name= 'Szkic';

        $template = '<span class="label ' . $checked . '">' .$name . '</span>';
        return $template;
    }

    public function getGroupsToDisplay ($groups)
    {
        $subscriberGroups = unserialize($groups);

        $template = '';
        foreach($subscriberGroups as $groupId) {
            $gruopName = $this->getSubscriberGroupTable()->getOneBy(array('id' => $groupId));
            $template .= '<span class="label label-info">' . $gruopName->getName() . '</span> ';
        }

        return $template;
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberGroupTable
     */
    public function getSubscriberGroupTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberGroupTable');
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