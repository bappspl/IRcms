<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Video\Controller\Video' => 'CmsIr\Video\Controller\VideoController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-video'  => __DIR__ . '/../view/partial/flashmessages-video.phtml',
            'partial/delete-video-modal'  => __DIR__ . '/../view/partial/delete-video-modal.phtml',
            'partial/delete-massive-video-modal'  => __DIR__ . '/../view/partial/delete-massive-video-modal.phtml',
            'partial/status-massive-video-modal'  => __DIR__ . '/../view/partial/status-massive-video-modal.phtml',
            'partial/language-video'  => __DIR__ . '/../view/partial/language.phtml',
        ),
        'template_path_stack' => array(
            'video' => __DIR__ . '/../view'
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
            'CmsIr\Video\Service\VideoService' => 'CmsIr\Video\Service\Factory\VideoService',
        ),
    ),
);