<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Category\Controller\Category' => 'CmsIr\Category\Controller\CategoryController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-category'  => __DIR__ . '/../view/partial/flashmessages-category.phtml',
            'partial/delete-category-modal'  => __DIR__ . '/../view/partial/delete-category-modal.phtml',
            'partial/delete-massive-category-modal'  => __DIR__ . '/../view/partial/delete-massive-category-modal.phtml',
            'partial/language-category'  => __DIR__ . '/../view/partial/language.phtml',
        ),
        'template_path_stack' => array(
            'category' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'CmsIr\Category\Service\CategoryService' => 'CmsIr\Category\Service\Factory\CategoryService',
        ),
    ),
);