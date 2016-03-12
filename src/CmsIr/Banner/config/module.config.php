<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Banner\Controller\Banner' => 'CmsIr\Banner\Controller\BannerController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-banner'  => __DIR__ . '/../view/partial/flashmessages-banner.phtml',
            'partial/delete-banner-modal'  => __DIR__ . '/../view/partial/delete-banner-modal.phtml',
            'partial/delete-massive-banner-modal'  => __DIR__ . '/../view/partial/delete-massive-banner-modal.phtml',
            'partial/status-massive-banner-modal'  => __DIR__ . '/../view/partial/status-massive-banner-modal.phtml',
        ),
        'template_path_stack' => array(
            'banner' => __DIR__ . '/../view'
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
            'CmsIr\Banner\Service\BannerService' => 'CmsIr\Banner\Service\Factory\BannerService',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'banner_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Banner/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'CmsIr\Banner\Entity' => 'banner_driver'
                )
            )
        )
    ),
    'strategies' => array(
        'ViewJsonStrategy',
    ),
);