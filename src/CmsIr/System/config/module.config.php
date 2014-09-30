<?php
return array(
    'controllers' => array(
        'invokables' => array(

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
            'formInput' => 'CmsIr\System\View\Helper\FormInput',
        ),
    ),
);