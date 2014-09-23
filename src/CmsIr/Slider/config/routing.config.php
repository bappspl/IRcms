<?php
return array(
    'slider' => array(
        'may_terminate' => true,
        'type' => 'Literal',
        'options' => array(
            'route' => '/cms-ir/slider',
            'defaults' => array(
                'module' => 'CmsIr\Slider',
                'controller' => 'CmsIr\Slider\Controller\Slider',
                'action' => 'list',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Slider',
                        'controller' => 'CmsIr\Slider\Controller\Slider',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:slider_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Slider',
                        'controller' => 'CmsIr\Slider\Controller\Slider',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'slider_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:slider_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Slider',
                        'controller' => 'CmsIr\Slider\Controller\Slider',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'slider_id' => '[0-9]+'
                    ),
                ),
            ),
        ),
    ),
);
