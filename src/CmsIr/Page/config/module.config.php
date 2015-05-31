<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Page\Controller\Page' => 'CmsIr\Page\Controller\PageController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages'  => __DIR__ . '/../view/partial/flashmessages.phtml',
            'partial/delete-modal'  => __DIR__ . '/../view/partial/delete-modal.phtml',
        ),
        'template_path_stack' => array(
            'page' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Form\FormAbstractServiceFactory',
        ),
        'factories' => array(
            'CmsIr\Page\Service\PageService' => 'CmsIr\Page\Service\Factory\PageService',
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
    'doctrine' => array(
        'driver' => array(
            'page_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Page/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'CmsIr\Page\Entity' => 'page_driver'
                )
            )
        )
    )
);