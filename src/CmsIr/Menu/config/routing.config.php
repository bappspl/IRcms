<?php
return array(
    'fake-menu' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/menu',
            'defaults' => array(
            ),
        ),
    ),
    'menu-list' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/menu',
            'defaults' => array(
                'module' => 'CmsIr\Menu',
                'controller' => 'CmsIr\Menu\Controller\Menu',
                'action'     => 'menuList',
            ),
        ),
    ),
    'menu-create' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/menu/create',
            'defaults' => array(
                'module' => 'CmsIr\Menu',
                'controller' => 'CmsIr\Menu\Controller\Menu',
                'action'     => 'create',
            ),
        ),
    ),
    'menu-edit' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/menu/edit/:id',
            'defaults' => array(
                'module' => 'CmsIr\Menu',
                'controller' => 'CmsIr\Menu\Controller\Menu',
                'action'     => 'edit',
            ),
            'constraints' => array(
                'id' => '[0-9]+'
            ),
        ),
    ),

    'menu-order' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/menu/order',
            'defaults' => array(
                'module' => 'CmsIr\Menu',
                'controller' => 'CmsIr\Menu\Controller\Menu',
                'action'     => 'order',
            ),
        ),
    ),

    'menu-delete-node' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/menu/menu-delete-node',
            'defaults' => array(
                'module' => 'CmsIr\Menu',
                'controller' => 'CmsIr\Menu\Controller\Menu',
                'action'     => 'deleteNode',
            ),
        ),
    ),

    'menu-edit-node' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/menu/menu-edit-node',
            'defaults' => array(
                'module' => 'CmsIr\Menu',
                'controller' => 'CmsIr\Menu\Controller\Menu',
                'action'     => 'editNode',
            ),
        ),
    ),

    'menu-create-node' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/menu/menu-create-node',
            'defaults' => array(
                'module' => 'CmsIr\Menu',
                'controller' => 'CmsIr\Menu\Controller\Menu',
                'action'     => 'createNode',
            ),
        ),
    ),
);