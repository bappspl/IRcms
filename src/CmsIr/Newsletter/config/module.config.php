<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'CmsIr\Newsletter\Controller\Newsletter' => 'CmsIr\Newsletter\Controller\NewsletterController',
            'CmsIr\Newsletter\Controller\Subscriber' => 'CmsIr\Newsletter\Controller\SubscriberController',
        ),
	),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages'  => __DIR__ . '/../view/partial/flashmessages.phtml',
            'partial/delete-group-modal'  => __DIR__ . '/../view/partial/delete-group-modal.phtml',
            'partial/delete-newsletter-modal'  => __DIR__ . '/../view/partial/delete-newsletter-modal.phtml',
            'partial/form/basic-data'  => __DIR__ . '/../view/partial/form/basic-data.phtml',
            'partial/form/basic-newsletter-data'  => __DIR__ . '/../view/partial/form/basic-newsletter-data.phtml',
            'partial/form/files'  => __DIR__ . '/../view/partial/form/files.phtml',
            'partial/form/actions'  => __DIR__ . '/../view/partial/form/actions.phtml',
            'partial/form/newsletter-actions'  => __DIR__ . '/../view/partial/form/newsletter-actions.phtml',
        ),
        'template_path_stack' => array(
            'newsletter' => __DIR__ . '/../view'
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