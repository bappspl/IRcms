<?php

namespace CmsIr\System\Console;

use CmsIr\File\Model\Gallery;
use CmsIr\Page\Model\Page;
use CmsIr\Video\Model\Video;
use Zend\Console\Prompt;
use Zend\Mvc\Controller\AbstractActionController;
use Faker\Factory;

class FakeDataCommand extends AbstractActionController
{
    public function createFakeDataAction()
    {
        /* @var $console \Zend\Console\Adapter\AdapterInterface */
        $console = $this->getServiceLocator()->get('console');

        $faker = Factory::create('pl_PL');

        for($i = 0; $i < 100; $i++) {
            $console->writeLine($i);

            $page = new Video();
            $page->setName($faker->name);
            $page->setStatusId(1);

            $this->getVideoTable()->save($page);
        }

        $console->writeLine('ok');
    }

    /**
     * @return \CmsIr\Page\Model\PageTable
     */
    public function getPageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Model\PageTable');
    }

    /**
     * @return \CmsIr\File\Model\GalleryTable
     */
    public function getGalleryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\GalleryTable');
    }

    /**
     * @return \CmsIr\Video\Model\VideoTable
     */
    public function getVideoTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Video\Model\VideoTable');
    }
}