<?php
return array(
    'post-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/post-list/news',
            'defaults' => array(
                'module' => 'CmsIr\Post',
                'controller' => 'CmsIr\Post\Controller\Post',
                'action'     => 'postList',
            ),
        ),
    ),
    'post' => array(
        'type'    => 'Segment',
        'may_terminate' => true,
        'options' => array(
            'route'    => '/cms-ir/post-list/:category',
            'defaults' => array(
                'module' => 'CmsIr\Post',
                'controller' => 'CmsIr\Post\Controller\Post',
                'action'     => 'postList',
            ),
            'constraints' => array(
                'category' => '[a-zA-Z0-9_-]+'
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Post',
                        'controller' => 'CmsIr\Post\Controller\Post',
                        'action'     => 'createPost',
                    ),
                    'constraints' => array(
                        'category' => '[a-zA-Z0-9_-]+'
                    ),
                ),
            ),
            'edit' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/edit/:post_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Post',
                        'controller' => 'CmsIr\Post\Controller\Post',
                        'action'     => 'editPost',
                    ),
                    'constraints' => array(
                        'category' => '[a-zA-Z0-9_-]+',
                        'post_id' =>  '[0-9]+'
                    ),
                ),
            ),
            'preview' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/preview/:post_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Post',
                        'controller' => 'CmsIr\Post\Controller\Post',
                        'action'     => 'previewPost',
                    ),
                    'constraints' => array(
                        'category' => '[a-zA-Z0-9_-]+',
                        'post_id' =>  '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete/:post_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Post',
                        'controller' => 'CmsIr\Post\Controller\Post',
                        'action'     => 'deletePost',
                    ),
                    'constraints' => array(
                        'category' => '[a-zA-Z0-9_-]+',
                        'post_id' =>  '[0-9]+'
                    ),
                ),
            ),
            'upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'CmsIr\Post',
                        'controller' => 'CmsIr\Post\Controller\Post',
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
                        'module' => 'CmsIr\Post',
                        'controller' => 'CmsIr\Post\Controller\Post',
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
                        'module' => 'CmsIr\Post',
                        'controller' => 'CmsIr\Post\Controller\Post',
                        'action'     => 'deletePhoto',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
        ),
    ),
);