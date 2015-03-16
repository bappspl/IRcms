<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\File\Controller\File' => 'CmsIr\File\Controller\FileController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-file'  => __DIR__ . '/../view/partial/flashmessages-file.phtml',
            'partial/delete-file-modal'  => __DIR__ . '/../view/partial/delete-file-modal.phtml'
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
);