<?php
return array(
    'category-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/category',
            'defaults' => array(
                'module' => 'CmsIr\Category',
                'controller' => 'CmsIr\Category\Controller\Category',
                'action'     => 'list',
            ),
        ),
    ),
    'category' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/category',
            'defaults' => array(
                'module' => 'CmsIr\Category',
                'controller' => 'CmsIr\Category\Controller\Category',
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
                        'module' => 'CmsIr\Category',
                        'controller' => 'CmsIr\Category\Controller\Category',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:category_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Category',
                        'controller' => 'CmsIr\Category\Controller\Category',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'category_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:category_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Category',
                        'controller' => 'CmsIr\Category\Controller\Category',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'category_id' => '[0-9]+'
                    ),
                ),
            ),
            'upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'CmsIr\Category',
                        'controller' => 'CmsIr\Category\Controller\Category',
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
                        'module' => 'CmsIr\Category',
                        'controller' => 'CmsIr\Category\Controller\Category',
                        'action'     => 'deletePhoto',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
        ),
    ),
);