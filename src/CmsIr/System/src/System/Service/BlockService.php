<?php

namespace CmsIr\System\Service;

use CmsIr\System\Model\Block;
use CmsIr\System\Model\Language;
use CmsIr\System\Util\Inflector;
use Zend\Db\Sql\Predicate\Like;
use Zend\Db\Sql\Predicate\Operator;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlockService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function saveBlocks($entity_id, $entity_type, $postArrayValues, $notEmpty = null)
    {
        $langArray = array();
        $languages = $this->getLanguageTable()->getAll();

        /* @var $lang Language */
        foreach ($languages as $lang) {
            $langArray[$lang->getUrlShortcut()] = $lang->getId();

            if($this->preg_array_key_exists('/' . $lang->getUrlShortcut() . '-url/', $postArrayValues) && strlen($postArrayValues[$lang->getUrlShortcut() . '-url']) == 0) {
                $postArrayValues[$lang->getUrlShortcut() . '-url'] = Inflector::slugify($postArrayValues['name']);
            }

            if($notEmpty && isset($postArrayValues[$lang->getUrlShortcut() . '-' . $notEmpty])) {
                if(strlen($postArrayValues[$lang->getUrlShortcut() . '-' . $notEmpty]) == 0) {
                    $postArrayValues[$lang->getUrlShortcut() . '-' . $notEmpty] = Inflector::slugify($postArrayValues['name']);
                }
            }
        }

        foreach($postArrayValues as $k => $value) {
            if(strpos($k, '-') !== false && strlen($value) > 0) {
                $split = explode('-', $k);
                $lang = $split[0];
                $name = $split[1];

                $block = $this->getBlockTable()->getOneBy(array(
                    'entity_id' => $entity_id,
                    'entity_type' => $entity_type,
                    'language_id' => $langArray[$lang],
                    'name' => $name
                ));

                if(!$block) {
                    $block = new Block();
                    $block->setEntityType($entity_type);
                    $block->setEntityId($entity_id);
                    $block->setLanguageId($langArray[$lang]);
                    $block->setName($name);
                }

                $block->setValue($value);

                $this->getBlockTable()->save($block);
            }
        }
    }

    public function getBlocks($entity, $entity_type)
    {
        $languagesArray = $this->getLanguageTable()->getAsAssocArray();

        $blocks = $this->getBlockTable()->getBy(array('entity_id' => $entity->getId(), 'entity_type' => $entity_type));
        $values = array();

        /* @var $block Block */
        foreach($blocks as $block) {
            $lang = $languagesArray[$block->getLanguageId()];
            $values[$lang . '-' . $block->getName()] = $block->getValue();
        }

        return $values;
    }

    public function findBlocksForEntityByLanguage($entity, $entity_type, $lang)
    {

    }

    public function search($searchWorld, $langId, $entityTypes = array())
    {
        $blockArray = array();

        foreach($entityTypes as $type) {
            $predicate = new PredicateSet();
            $predicate->andPredicate(new Operator('entity_type', Operator::OP_EQ, $type));
            $predicate->andPredicate(new Operator('language_id', Operator::OP_EQ, $langId));
            $predicate->andPredicate(new Like('value', '%' . $searchWorld . '%'));

            $predicate1 = new PredicateSet();
            $predicate1->orPredicate(new Operator('name', Operator::OP_EQ, 'title'));
            $predicate1->orPredicate(new Operator('name', Operator::OP_EQ, 'content'));
            $predicate1->orPredicate(new Operator('name', Operator::OP_EQ, 'product_name'));
            $predicate1->orPredicate(new Operator('name', Operator::OP_EQ, 'description'));

            $finalPredicate = new PredicateSet();
            $finalPredicate->andPredicate($predicate);
            $finalPredicate->andPredicate($predicate1);

            $blocks = $this->getBlockTable()->getBy($finalPredicate);

            $blocks = array_values($blocks);

            foreach($blocks as $key => $block) {
                if($key > 0) {
                    $idOld = $blocks[$key-1]->getId();
                    $idNew = $blocks[$key]->getId();

                    $entityIdOld = $blocks[$key-1]->getEntityId();
                    $entityIdNew = $blocks[$key]->getEntityId();

                    $valueIdOld = $blocks[$key-1]->getValue();
                    $valueIdNew = $blocks[$key]->getValue();

                    if(($idNew != $idOld) && ($entityIdNew != $entityIdOld) && ($valueIdNew != $valueIdOld)) {

                        switch($type) {
                            case 'Product':
                                $entity = $this->getProductService()->findProductWithBlocksSearch($block, $langId);
                                break;
                            case 'Page':
                                $entity = $this->getPageService()->findPageWithBlocksSearch($block, $langId);
                                break;
                            case 'Post':
                                $entity = $this->getPostService()->findPostWithBlocksSearch($block, $langId);
                                break;
                        }

                        if($entity) {
                            array_push($blockArray, $entity);
                        }
                    }
                } else if($key == 0) {
                    switch($type) {
                        case 'Product':
                            $entity = $this->getProductService()->findProductWithBlocksSearch($block, $langId);
                            break;
                        case 'Page':
                            $entity = $this->getPageService()->findPageWithBlocksSearch($block, $langId);
                            break;
                        case 'Post':
                            $entity = $this->getPostService()->findPostWithBlocksSearch($block, $langId);
                            break;
                    }

                    if($entity) {
                        array_push($blockArray, $entity);
                    }
                }
            }
        }

        return $blockArray;
    }

    private function preg_array_key_exists($pattern, $array)
    {
        $keys = array_keys($array);
        return (int) preg_grep($pattern,$keys);
    }

    /**
     * @return \CmsIr\System\Model\BlockTable
     */
    public function getBlockTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\BlockTable');
    }


    /**
     * @return \CmsIr\System\Model\LanguageTable
     */
    public function getLanguageTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\LanguageTable');
    }

    /**
     * @return \Product\Service\ProductService
     */
    public function getProductService()
    {
        return $this->getServiceLocator()->get('Product\Service\ProductService');
    }

    /**
     * @return \CmsIr\Page\Service\PageService
     */
    public function getPageService()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Service\PageService');
    }

    /**
     * @return \CmsIr\Post\Service\PostService
     */
    public function getPostService()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Service\PostService');
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
