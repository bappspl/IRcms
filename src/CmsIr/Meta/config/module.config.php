<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Meta\Controller\Meta' => 'CmsIr\Meta\Controller\MetaController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/delete-meta-modal'  => __DIR__ . '/../view/partial/delete-meta-modal.phtml',
            'partial/meta-form'  => __DIR__ . '/../view/partial/meta-form.phtml'
        ),
        'template_path_stack' => array(
            'meta' => __DIR__ . '/../view'
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
            'CmsIr\Meta\Service\MetaService' => 'CmsIr\Meta\Service\Factory\MetaService',
        ),
    ),
    'view_helpers' => array(
        'invokables'=> array(
            'metaHelper' => 'CmsIr\Meta\View\Helper\MetaHelper',
        ),
    ),
);