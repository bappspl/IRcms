<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'CmsIr\Authentication\Controller\Index' => 'CmsIr\Authentication\Controller\IndexController'
        ),
	),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'authentication' => __DIR__ . '/../view'
        ),

		'display_exceptions' => true,
    ),
	'service_manager' => array(
		'aliases' => array(
			'Zend\Authentication\AuthenticationService' => 'my_auth_service',
		),
		'invokables' => array(
			'my_auth_service' => 'Zend\Authentication\AuthenticationService',
		),
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