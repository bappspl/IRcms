<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'CmsIr\Users\Controller\Users' => 'CmsIr\Users\Controller\UsersController'
        ),
	),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages'  => __DIR__ . '/../view/partial/flashmessages.phtml',
            'partial/delete-user-modal'  => __DIR__ . '/../view/partial/delete-user-modal.phtml',
            'partial/delete-massive-users-modal'  => __DIR__ . '/../view/partial/delete-massive-users-modal.phtml',
            'partial/form/basic-data'  => __DIR__ . '/../view/partial/form/basic-data.phtml',
            'partial/form/user-files'  => __DIR__ . '/../view/partial/form/user-files.phtml',
            'partial/form/actions-users'  => __DIR__ . '/../view/partial/form/actions-users.phtml',
        ),
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
);