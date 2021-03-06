<?php
return array(
    'category-main' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/category',
            'defaults' => array(
                'module' => 'CmsIr\Category',
                'controller' => 'CmsIr\Category\Controller\Category',
                'action'     => 'list',
            ),
        )
    ),
    'category-upload' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/category-upload',
            'defaults' => array(
                'module' => 'CmsIr\Category',
                'controller' => 'CmsIr\Category\Controller\Category',
                'action'     => 'list',
            ),
        ),
        'child_routes' => array(
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
                        'action'     => 'uploadFiles',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'upload-main' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload-main',
                    'defaults' => array(
                        'module' => 'CmsIr\Category',
                        'controller' => 'CmsIr\Category\Controller\Category',
                        'action'     => 'uploadFilesMain',
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
            'delete-photo-main' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete-photo-main',
                    'defaults' => array(
                        'module' => 'CmsIr\Category',
                        'controller' => 'CmsIr\Category\Controller\Category',
                        'action'     => 'deletePhotoMain',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
        ),
    ),
    'category' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/category[/:type]',
            'defaults' => array(
                'module' => 'CmsIr\Category',
                'controller' => 'CmsIr\Category\Controller\Category',
                'action'     => 'list',
            ),
            'constraints' => array(
                'category' => '[a-zA-Z0-9_-]+',
                'type' => '[a-zA-Z0-9_-]+'
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
            'change-position' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-position',
                    'defaults' => array(
                        'module' => 'CmsIr\Category',
                        'controller' => 'CmsIr\Category\Controller\Category',
                        'action'     => 'changePosition',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
        ),
    ),
);