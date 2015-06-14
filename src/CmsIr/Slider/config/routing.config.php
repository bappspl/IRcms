<?php
return array(
    'slider-main' => array(
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
    ),
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
            'upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'CmsIr\Slider',
                        'controller' => 'CmsIr\Slider\Controller\Slider',
                        'action'     => 'upload',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'order' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/order',
                    'defaults' => array(
                        'module' => 'CmsIr\Slider',
                        'controller' => 'CmsIr\Slider\Controller\Slider',
                        'action'     => 'order',
                    ),
                ),
            ),
            'change-status' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-status/:slider_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Slider',
                        'controller' => 'CmsIr\Slider\Controller\Slider',
                        'action'     => 'changeStatus',
                    ),
                    'constraints' => array(
                        'slider_id' =>  '[0-9]+'
                    ),
                ),
            ),
            'items' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/items/:slider_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Slider',
                        'controller' => 'CmsIr\Slider\Controller\Slider',
                        'action' => 'items',
                    ),
                    'constraints' => array(
                        'slider_id' => '[0-9]+'
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
                                'action' => 'createItem',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit/:item_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Slider',
                                'controller' => 'CmsIr\Slider\Controller\Slider',
                                'action' => 'editItem',
                            ),
                            'constraints' => array(
                                'item_id' => '[0-9]+'
                            ),
                        ),
                    ),
                    'delete' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/delete/:item_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Slider',
                                'controller' => 'CmsIr\Slider\Controller\Slider',
                                'action' => 'deleteItem',
                            ),
                            'constraints' => array(
                                'item_id' => '[0-9]+'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
