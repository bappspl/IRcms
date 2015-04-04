<?php
return array(
    'users-main' => array(
        'may_terminate' => true,
        'type'    => 'Literal',
        'options' => array(
            'route'    => '/cms-ir/users',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Users',
                'action'     => 'usersList',
            ),
        ),
    ),
    'users' => array(
        'may_terminate' => true,
        'type'    => 'Literal',
        'options' => array(
            'route'    => '/cms-ir/users',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Users',
                'action'     => 'usersList',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Users',
                        'controller' => 'CmsIr\Users\Controller\Users',
                        'action'     => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/edit/:id',
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
            'preview' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/preview/:id',
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
            'delete' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete/:id',
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
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'CmsIr\Users',
                        'controller' => 'CmsIr\Users\Controller\Users',
                        'action'     => 'upload',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'change-password' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-password',
                    'defaults' => array(
                        'module' => 'CmsIr\Users',
                        'controller' => 'CmsIr\Users\Controller\Users',
                        'action'     => 'changePassword',
                    ),
                ),
            ),
        ),
    ),
);