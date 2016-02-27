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
            'upload-main' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload-main',
                    'defaults' => array(
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\Gallery',
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
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\Gallery',
                        'action'     => 'deletePhoto',
                    ),
                ),
            ),
            'change-status' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-status/:gallery_id',
                    'defaults' => array(
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\Gallery',
                        'action'     => 'changeStatus',
                    ),
                    'constraints' => array(
                        'gallery_id' =>  '[0-9]+'
                    ),
                ),
            ),
            'delete-photo-main' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete-photo-main',
                    'defaults' => array(
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\Gallery',
                        'action'     => 'deletePhotoMain',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'change-position' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-position',
                    'defaults' => array(
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\Gallery',
                        'action'     => 'changePosition',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
        ),
    ),
);