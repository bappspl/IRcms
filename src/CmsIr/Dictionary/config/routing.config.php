<?php
return array(
    'dictionary-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/dictionary/position',
            'defaults' => array(
                'module' => 'CmsIr\Dictionary',
                'controller' => 'CmsIr\Dictionary\Controller\Dictionary',
                'action'     => 'list',
            ),
        ),
    ),
    'dictionary' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/dictionary/:category',
            'defaults' => array(
                'module' => 'CmsIr\Dictionary',
                'controller' => 'CmsIr\Dictionary\Controller\Dictionary',
                'action'     => 'list',
            ),
            'constraints' => array(
                'category' => '[a-zA-Z0-9_-]+'
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Dictionary',
                        'controller' => 'CmsIr\Dictionary\Controller\Dictionary',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:dictionary_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Dictionary',
                        'controller' => 'CmsIr\Dictionary\Controller\Dictionary',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'dictionary_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:dictionary_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Dictionary',
                        'controller' => 'CmsIr\Dictionary\Controller\Dictionary',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'dictionary_id' => '[0-9]+'
                    ),
                ),
            ),
            'upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'CmsIr\Dictionary',
                        'controller' => 'CmsIr\Dictionary\Controller\Dictionary',
                        'action'     => 'upload',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'delete-photo' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete-photo',
                    'defaults' => array(
                        'module' => 'CmsIr\Dictionary',
                        'controller' => 'CmsIr\Dictionary\Controller\Dictionary',
                        'action'     => 'deletePhoto',
                    ),
                ),
            ),
        ),
    ),
);