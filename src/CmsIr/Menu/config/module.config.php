<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Menu\Controller\Menu' => 'CmsIr\Menu\Controller\MenuController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages'  => __DIR__ . '/../view/partial/flashmessages.phtml',
            'partial/delete-node-modal'  => __DIR__ . '/../view/partial/delete-node-modal.phtml',
            'partial/edit-node-modal'  => __DIR__ . '/../view/partial/edit-node-modal.phtml',
            'partial/edit-page-node-modal'  => __DIR__ . '/../view/partial/edit-page-node-modal.phtml',
            'partial/create-node-modal'  => __DIR__ . '/../view/partial/create-node-modal.phtml',
            'partial/create-page-node-modal'  => __DIR__ . '/../view/partial/create-page-node-modal.phtml',
            'partial/slider/form/basic-data'  => __DIR__ . '/../view/partial/form/basic-data.phtml',
            'partial/slider/form/actions'  => __DIR__ . '/../view/partial/form/actions.phtml',
            'partial/create-page-part-node-modal'  => __DIR__ . '/../view/partial/create-page-part-node-modal.phtml',

        ),
        'template_path_stack' => array(
            'menu' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Form\FormAbstractServiceFactory',
        ),
        'factories' => array(
            'CmsIr\Menu\Service\MenuService' => 'CmsIr\Menu\Service\Factory\MenuService',
        ),
    ),
    'strategies' => array(
        'ViewJsonStrategy',
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
);