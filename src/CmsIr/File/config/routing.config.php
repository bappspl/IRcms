<?php
return array(
    'file-main-document' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/file/document',
            'defaults' => array(
                'module' => 'CmsIr\File',
                'controller' => 'CmsIr\File\Controller\File',
                'action'     => 'list',
            ),
        ),
    ),
    'file-main-gallery' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/file/gallery',
            'defaults' => array(
                'module' => 'CmsIr\File',
                'controller' => 'CmsIr\File\Controller\File',
                'action'     => 'list',
            ),
        ),
    ),
    'file' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/file/:category',
            'defaults' => array(
                'module' => 'CmsIr\File',
                'controller' => 'CmsIr\File\Controller\File',
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
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\File',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:file_id',
                    'defaults' => array(
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\File',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'file_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:file_id',
                    'defaults' => array(
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\File',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'file_id' => '[0-9]+'
                    ),
                ),
            ),
            'upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\File',
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
                        'module' => 'CmsIr\File',
                        'controller' => 'CmsIr\File\Controller\File',
                        'action'     => 'deletePhoto',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
        ),
    ),
);