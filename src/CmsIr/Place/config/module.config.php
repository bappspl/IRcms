<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Place\Controller\Place' => 'CmsIr\Place\Controller\PlaceController'
        ),
    ),
    //'cms_regions' => include __DIR__ . '/regions.config.php',
    'router' => array(
        'routes' => include __DIR__ . '/routing.config.php',
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Form\FormAbstractServiceFactory',
        ),
        'factories' => array(
            'CmsIr\Place\Service\PlaceService' => 'CmsIr\Place\Service\Factory\PlaceService'
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages-place'  => __DIR__ . '/../view/partial/flashmessages-place.phtml',
            'partial/delete-place-modal'  => __DIR__ . '/../view/partial/delete-place-modal.phtml',
            'partial/delete-massive-place-modal'  => __DIR__ . '/../view/partial/delete-massive-place-modal.phtml',
        ),
        'template_path_stack' => array(
            'place' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
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
