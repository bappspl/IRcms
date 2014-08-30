<?php
return array(
    'login' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/login',
            'defaults' => array(
                'module' => 'CmsIr\Authentication',
                'controller' => 'CmsIr\Authentication\Controller\Index',
                'action'     => 'login',
            ),
        ),
    ),
    'forgotten-password' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/forgotten-password',
            'defaults' => array(
                'module' => 'CmsIr\Authentication',
                'controller' => 'CmsIr\Authentication\Controller\Index',
                'action'     => 'forgottenPassword',
            ),
        ),
    ),
    'registration' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/registration',
            'defaults' => array(
                'module' => 'CmsIr\Authentication',
                'controller' => 'CmsIr\Authentication\Controller\Index',
                'action'     => 'registration',
            ),
        ),
    ),
    'logout' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/logout',
            'defaults' => array(
                'module' => 'CmsIr\Authentication',
                'controller' => 'CmsIr\Authentication\Controller\Index',
                'action'     => 'logout',
            ),
        ),
    ),
    'confirm-email' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/confirm-email[/:id]',
            'defaults' => array(
                'module' => 'CmsIr\Authentication',
                'controller' => 'CmsIr\Authentication\Controller\Index',
                'action'     => 'confirmEmail',
            ),
            'constraints' => array(
                'id' => '[a-zA-Z0-9_-]+'
            ),
        ),
    ),
);