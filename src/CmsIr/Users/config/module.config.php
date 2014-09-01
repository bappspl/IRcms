<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'CmsIr\Users\Controller\Index' => 'CmsIr\Users\Controller\IndexController'
        ),
	),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'users' => __DIR__ . '/../view'
        ),
		'display_exceptions' => true,
    ),
	'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Form\FormAbstractServiceFactory',
        ),
	),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
);