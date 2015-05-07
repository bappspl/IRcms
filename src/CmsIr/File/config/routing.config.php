<?php
return array(
    'gallery-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/gallery',
            'defaults' => array(
                'module' => 'CmsIr\File',
                'controller' => 'CmsIr\File\Controller\Gallery',
                'action'     => 'list',
            ),
        ),
    ),
    'gallery' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/gallery',
            'defaults' => array(
                'module' => 'CmsIr\File',
                'controller' => 'CmsIr\File\Controller\Gallery',
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
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\Gallery',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:gallery_id',
                    'defaults' => array(
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\Gallery',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'gallery_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:gallery_id',
                    'defaults' => array(
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\Gallery',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'gallery_id' => '[0-9]+'
                    ),
                ),
            ),
            'upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\Gallery',
                        'action'     => 'uploadFiles',
                    ),
                ),
            ),
            'delete-photo' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete-photo',
                    'defaults' => array(
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\Gallery',
                        'action'     => 'deletePhoto',
                    ),
                ),
            ),
        ),
    ),
);