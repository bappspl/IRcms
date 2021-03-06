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
            'partial/send-newsletter-modal'  => __DIR__ . '/../view/partial/send-newsletter-modal.phtml',
            'partial/delete-group-modal'  => __DIR__ . '/../view/partial/delete-group-modal.phtml',
            'partial/delete-newsletter-modal'  => __DIR__ . '/../view/partial/delete-newsletter-modal.phtml',
            'partial/delete-subscriber-modal'  => __DIR__ . '/../view/partial/delete-subscriber-modal.phtml',
            'partial/form/basic-subscriber-group-data'  => __DIR__ . '/../view/partial/form/basic-subscriber-group-data.phtml',
            'partial/form/basic-newsletter-data'  => __DIR__ . '/../view/partial/form/basic-newsletter-data.phtml',
            'partial/form/basic-subscriber-data'  => __DIR__ . '/../view/partial/form/basic-subscriber-data.phtml',
            'partial/form/files'  => __DIR__ . '/../view/partial/form/files.phtml',
            'partial/form/actions'  => __DIR__ . '/../view/partial/form/actions.phtml',
            'partial/form/actions-subscriber'  => __DIR__ . '/../view/partial/form/actions-subscriber.phtml',
            'partial/form/newsletter-actions'  => __DIR__ . '/../view/partial/form/newsletter-actions.phtml',

            'partial/delete-massive-newsletter-modal'  => __DIR__ . '/../view/partial/delete-massive-newsletter-modal.phtml',
            'partial/status-massive-newsletter-modal'  => __DIR__ . '/../view/partial/status-massive-newsletter-modal.phtml',
            'partial/delete-massive-subscriber-modal'  => __DIR__ . '/../view/partial/delete-massive-subscriber-modal.phtml',
            'partial/status-massive-subscriber-modal'  => __DIR__ . '/../view/partial/status-massive-subscriber-modal.phtml',
            'partial/delete-massive-subscriber-group-modal'  => __DIR__ . '/../view/partial/delete-massive-subscriber-group-modal.phtml',
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
        'factories' => array(
            'CmsIr\Newsletter\Service\NewsletterService' => 'CmsIr\Newsletter\Service\Factory\NewsletterService',
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