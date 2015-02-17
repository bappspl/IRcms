<?php
return array(
    'menu-main' => array(
        'may_terminate' => true,
        'type'    => 'Literal',
        'options' => array(
            'route'    => '/cms-ir/menu',
            'defaults' => array(
                'module' => 'CmsIr\Menu',
                'controller' => 'CmsIr\Menu\Controller\Menu',
                'action' => 'menuList',
            ),
        ),
    ),
    'menu' => array(
        'may_terminate' => true,
        'type'    => 'Literal',
        'options' => array(
            'route'    => '/cms-ir/menu',
            'defaults' => array(
                'module' => 'CmsIr\Menu',
                'controller' => 'CmsIr\Menu\Controller\Menu',
                'action'     => 'menuList',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Menu',
                        'controller' => 'CmsIr\Menu\Controller\Menu',
                        'action'     => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/edit/:id',
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
            'order' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/order',
                    'defaults' => array(
                        'module' => 'CmsIr\Menu',
                        'controller' => 'CmsIr\Menu\Controller\Menu',
                        'action'     => 'order',
                    ),
                ),
            ),
            'delete-node' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/menu-delete-node',
                    'defaults' => array(
                        'module' => 'CmsIr\Menu',
                        'controller' => 'CmsIr\Menu\Controller\Menu',
                        'action'     => 'deleteNode',
                    ),
                ),
            ),
            'edit-node' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/menu-edit-node',
                    'defaults' => array(
                        'module' => 'CmsIr\Menu',
                        'controller' => 'CmsIr\Menu\Controller\Menu',
                        'action'     => 'editNode',
                    ),
                ),
            ),
            'create-node' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/menu-create-node',
                    'defaults' => array(
                        'module' => 'CmsIr\Menu',
                        'controller' => 'CmsIr\Menu\Controller\Menu',
                        'action'     => 'createNode',
                    ),
                ),
            ),
        ),
    ),
);