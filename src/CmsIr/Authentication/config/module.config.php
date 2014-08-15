<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'CmsIr\Authentication\Controller\Index' => 'CmsIr\Authentication\Controller\IndexController'
        ),
	),
    'router' => array(
        'routes' => array(
			'auth' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/login',
					'defaults' => array(
                        'module' => 'CmsIr\Authentication',
                        'controller' => 'CmsIr\Authentication\Controller\Index',
                        'action'     => 'login',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:controller[/:action[/:id]]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     	 => '[a-zA-Z0-9_-]*',
							),
							'defaults' => array(
							),
						),
					),
				),
			),			
		),
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
	),
);