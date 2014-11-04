<?php
return array(
    'fake-post' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/post-list',
            'defaults' => array(
            ),
        ),
    ),
    'post-list' => array(
        'type'    => 'Segment',
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
    ),
    'post-create' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/post/:category/create',
            'defaults' => array(
                'module' => 'CmsIr\Post',
                'controller' => 'CmsIr\Post\Controller\Post',
                'action'     => 'create',
            ),
            'constraints' => array(
                'category' => '[a-zA-Z0-9_-]+'
            ),
        ),
    ),
);