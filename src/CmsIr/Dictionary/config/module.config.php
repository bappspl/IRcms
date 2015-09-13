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
            'partial/delete-dictionary-modal'  => __DIR__ . '/../view/partial/delete-dictionary-modal.phtml',
            'partial/delete-massive-dictionary-modal'  => __DIR__ . '/../view/partial/delete-massive-dictionary-modal.phtml',
            'partial/language-dictionary'  => __DIR__ . '/../view/partial/language.phtml',
        ),
        'template_path_stack' => array(
            'dictionary' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Form\FormAbstractServiceFactory',
        ),
        'factories' => array(
            'CmsIr\Dictionary\Service\DictionaryService' => 'CmsIr\Dictionary\Service\Factory\DictionaryService',
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