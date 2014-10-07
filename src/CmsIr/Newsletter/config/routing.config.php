<?php
return array(
    'fake-newsletter' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/newsletter',
            'defaults' => array(
            ),
        ),
    ),
    'newsletter' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/newsletter',
            'defaults' => array(
                'module' => 'CmsIr\Newsletter',
                'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                'action'     => 'newsletter',
            ),
        ),
    ),
    'subscriber-list' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/newsletter/subscriber-list',
            'defaults' => array(
                'module' => 'CmsIr\Newsletter',
                'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                'action'     => 'subscriberList',
            ),
        ),
    ),
    'subscriber-group' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/newsletter/subscriber-group',
            'defaults' => array(
                'module' => 'CmsIr\Newsletter',
                'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                'action'     => 'subscriberGroup',
            ),
        ),
    ),
    'newsletter-settings' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/newsletter/newsletter-settings',
            'defaults' => array(
                'module' => 'CmsIr\Newsletter',
                'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                'action'     => 'newsletterSettings',
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
);