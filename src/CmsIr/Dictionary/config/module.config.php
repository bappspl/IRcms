<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Dictionary\Controller\Dictionary' => 'CmsIr\Dictionary\Controller\DictionaryController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-dictionary'  => __DIR__ . '/../view/partial/flashmessages-dictionary.phtml',
            'partial/delete-dictionary-modal'  => __DIR__ . '/../view/partial/delete-dictionary-modal.phtml'
        ),
        'template_path_stack' => array(
            'dictionary' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
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
            'dictionary_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Dictionary/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'CmsIr\Dictionary\Entity' => 'dictionary_driver'
                )
            )
        )
    )
);