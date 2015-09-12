<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Tag\Controller\Tag' => 'CmsIr\Tag\Controller\TagController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-tag'  => __DIR__ . '/../view/partial/flashmessages-tag.phtml',
            'partial/delete-tag-modal'  => __DIR__ . '/../view/partial/delete-tag-modal.phtml',
            'partial/delete-massive-tag-modal'  => __DIR__ . '/../view/partial/delete-massive-tag-modal.phtml',
            'partial/language-tag'  => __DIR__ . '/../view/partial/language.phtml',
        ),
        'template_path_stack' => array(
            'tag' => __DIR__ . '/../view'
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
            'CmsIr\Tag\Service\TagService' => 'CmsIr\Tag\Service\Factory\TagService',
        ),
    ),
);