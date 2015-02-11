<?php
return array(
    'fake-users' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users',
            'defaults' => array(
            ),
        ),
    ),
    'users-list' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Users',
                'action'     => 'usersList',
            ),
        ),
    ),
    'user-create' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users/create',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Users',
                'action'     => 'create',
            ),
        ),
    ),
    'user-edit' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users/edit/:id',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Users',
                'action'     => 'edit',
            ),
            'constraints' => array(
                'id' => '[0-9]+'
            ),
        ),
    ),
    'user-preview' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users/preview/:id',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Users',
                'action'     => 'preview',
            ),
            'constraints' => array(
                'id' => '[0-9]+'
            ),
        ),
    ),
    'user-delete' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users/delete/:id',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Users',
                'action'     => 'delete',
            ),
            'constraints' => array(
                'id' => '[0-9]+'
            ),
        ),
    ),

    'upload' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users/upload',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Users',
                'action'     => 'upload',
            ),
            'constraints' => array(
            ),
        ),
    ),

    'user-change-password' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users/change-password',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Users',
                'action'     => 'changePassword',
            ),
        ),
    ),
);