<?php
return array(
    'fake' => array(
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
                'controller' => 'CmsIr\Users\Controller\Index',
                'action'     => 'usersList',
            ),
        ),
    ),
    'users-create' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/users/create',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Index',
                'action'     => 'create',
            ),
        ),
    ),
);