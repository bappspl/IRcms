<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\File\Controller\Gallery' => 'CmsIr\File\Controller\GalleryController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-file'  => __DIR__ . '/../view/partial/flashmessages-file.phtml',
            'partial/delete-file-modal'  => __DIR__ . '/../view/partial/delete-file-modal.phtml',
            'partial/delete-massive-file-modal'  => __DIR__ . '/../view/partial/delete-massive-file-modal.phtml',
            'partial/status-massive-file-modal'  => __DIR__ . '/../view/partial/status-massive-file-modal.phtml',
            'partial/language-file'  => __DIR__ . '/../view/partial/language.phtml',
        ),
        'template_path_stack' => array(
            'file' => __DIR__ . '/../view'
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
            'CmsIr\File\Service\FileService' => 'CmsIr\File\Service\Factory\FileService',
            'CmsIr\File\Service\GalleryService' => 'CmsIr\File\Service\Factory\GalleryService',
        ),
    ),
);