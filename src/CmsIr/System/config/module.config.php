<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\System\Controller\System' => 'CmsIr\System\Controller\SystemController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
        ),
        'template_path_stack' => array(
            'system' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Form\FormAbstractServiceFactory',
        ),
        'factories' => array(
            'CmsIr\System\Service\StatusService' => 'CmsIr\System\Service\Factory\StatusService',
            'CmsIr\System\Logger\Logger' => 'CmsIr\System\logger\Factory\Logger',
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
    'view_helpers' => array(
        'invokables'=> array(
            'customFormHelper' => 'CmsIr\System\View\Helper\FormInput',
        ),
    ),
);