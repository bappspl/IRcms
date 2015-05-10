<?php
return array(
    'page-main' => array(
        'may_terminate' => true,
        'type' => 'Literal',
        'options' => array(
            'route' => '/cms-ir/page',
            'defaults' => array(
                'module' => 'CmsIr\Page',
                'controller' => 'CmsIr\Page\Controller\Page',
                'action' => 'list',
            ),
        ),
    ),
    'page' => array(
        'may_terminate' => true,
        'type' => 'Literal',
        'options' => array(
            'route' => '/cms-ir/page',
            'defaults' => array(
                'module' => 'CmsIr\Page',
                'controller' => 'CmsIr\Page\Controller\Page',
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
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:page_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'page_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:page_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'page_id' => '[0-9]+'
                    ),
                ),
            ),
            'upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
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
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
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
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
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
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action'     => 'deletePhotoMain',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
        ),
    ),
);
