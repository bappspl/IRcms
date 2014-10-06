<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Slider\Controller\Slider' => 'CmsIr\Slider\Controller\SliderController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/flashmessages'  => __DIR__ . '/../view/partial/flashmessages.phtml',
            'partial/delete-modal'  => __DIR__ . '/../view/partial/delete-modal.phtml',
            'partial/delete-item-modal'  => __DIR__ . '/../view/partial/delete-item-modal.phtml',
        ),
        'template_path_stack' => array(
            'slider' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Form\FormAbstractServiceFactory',
        ),
        'factories' => array(
            'CmsIr\Slider\Service\SliderService' => 'CmsIr\Slider\Service\Factory\SliderService',
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