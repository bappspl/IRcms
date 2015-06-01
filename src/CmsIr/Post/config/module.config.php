<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'CmsIr\Post\Controller\Post' => 'CmsIr\Post\Controller\PostController'
        ),
	),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-post'  => __DIR__ . '/../view/partial/flashmessages-post.phtml',
            'partial/delete-post-modal'  => __DIR__ . '/../view/partial/delete-post-modal.phtml',
            'partial/delete-massive-post-modal'  => __DIR__ . '/../view/partial/delete-massive-post-modal.phtml',
            'partial/status-massive-post-modal'  => __DIR__ . '/../view/partial/status-massive-post-modal.phtml',
            'partial/form/basic-post-data'  => __DIR__ . '/../view/partial/form/basic-post-data.phtml',
            'partial/form/files'  => __DIR__ . '/../view/partial/form/files.phtml',
            'partial/form/actions-post'  => __DIR__ . '/../view/partial/form/actions-post.phtml',
        ),
        'template_path_stack' => array(
            'post' => __DIR__ . '/../view'
        ),
		'display_exceptions' => true,
    ),
	'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Form\FormAbstractServiceFactory',
        ),
        'factories' => array(
            'CmsIr\Post\Service\PostService' => 'CmsIr\Post\Service\Factory\PostService',
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