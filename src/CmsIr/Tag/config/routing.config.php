<?php
return array(
    'tag-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/tag',
            'defaults' => array(
                'module' => 'CmsIr\Tag',
                'controller' => 'CmsIr\Tag\Controller\Tag',
                'action'     => 'list',
            ),
        ),
    ),
    'tag' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/tag',
            'defaults' => array(
                'module' => 'CmsIr\Tag',
                'controller' => 'CmsIr\Tag\Controller\Tag',
                'action'     => 'list',
            ),
            'constraints' => array(
                'tag' => '[a-zA-Z0-9_-]+'
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Tag',
                        'controller' => 'CmsIr\Tag\Controller\Tag',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:tag_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Tag',
                        'controller' => 'CmsIr\Tag\Controller\Tag',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'tag_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:tag_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Tag',
                        'controller' => 'CmsIr\Tag\Controller\Tag',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'tag_id' => '[0-9]+'
                    ),
                ),
            ),
            'change-position' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-position',
                    'defaults' => array(
                        'module' => 'CmsIr\Tag',
                        'controller' => 'CmsIr\Tag\Controller\Tag',
                        'action'     => 'changePosition',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
        ),
    ),
);