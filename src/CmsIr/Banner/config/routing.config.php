<?php
return array(
    'banner-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/banner',
            'defaults' => array(
                'module' => 'CmsIr\Banner',
                'controller' => 'CmsIr\Banner\Controller\Banner',
                'action'     => 'list',
            ),
        ),
    ),
    'banner' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/banner',
            'defaults' => array(
                'module' => 'CmsIr\Banner',
                'controller' => 'CmsIr\Banner\Controller\Banner',
                'action'     => 'list',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Banner',
                        'controller' => 'CmsIr\Banner\Controller\Banner',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:banner_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Banner',
                        'controller' => 'CmsIr\Banner\Controller\Banner',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'banner_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:banner_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Banner',
                        'controller' => 'CmsIr\Banner\Controller\Banner',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'banner_id' => '[0-9]+'
                    ),
                ),
            ),
            'upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'CmsIr\Banner',
                        'controller' => 'CmsIr\Banner\Controller\Banner',
                        'action'     => 'upload',
                    ),
                ),
            ),
            'delete-photo' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete-photo',
                    'defaults' => array(
                        'module' => 'CmsIr\Banner',
                        'controller' => 'CmsIr\Banner\Controller\Banner',
                        'action'     => 'deletePhoto',
                    ),
                ),
            ),
        ),
    ),
);