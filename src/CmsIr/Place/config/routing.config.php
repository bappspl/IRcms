<?php

return array(
    'place-main' => array(
        'may_terminate' => true,
        'type' => 'Literal',
        'options' => array(
            'route' => '/cms-ir/place',
            'defaults' => array(
                'module' => 'CmsIr\Place',
                'controller' => 'CmsIr\Place\Controller\Place',
                'action' => 'list',
            ),
        ),
    ),
    'place' => array(
        'may_terminate' => true,
        'type' => 'Literal',
        'options' => array(
            'route' => '/cms-ir/place',
            'defaults' => array(
                'module' => 'CmsIr\Place',
                'controller' => 'CmsIr\Place\Controller\Place',
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
                        'module' => 'CmsIr\Place',
                        'controller' => 'CmsIr\Place\Controller\Place',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:id',
                    'defaults' => array(
                        'module' => 'CmsIr\Place',
                        'controller' => 'CmsIr\Place\Controller\Place',
                        'action' => 'edit',
                    ),
                ),
            ),
            'preview' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/preview/:id',
                    'defaults' => array(
                        'module' => 'CmsIr\Place',
                        'controller' => 'CmsIr\Place\Controller\Place',
                        'action' => 'preview',
                    ),
                ),
            ),
            'delete' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete/:id',
                    'defaults' => array(
                        'module' => 'CmsIr\Post',
                        'controller' => 'CmsIr\Place\Controller\Place',
                        'action'     => 'delete',
                    ),
                    'constraints' => array(
                        'post_id' =>  '[0-9]+'
                    ),
                ),
            ),
            'change-position' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-position',
                    'defaults' => array(
                        'module' => 'CmsIr\Post',
                        'controller' => 'CmsIr\Place\Controller\Place',
                        'action'     => 'changePosition',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
        )
    )
);