<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\Dashboard\Controller\Index' => 'CmsIr\Dashboard\Controller\IndexController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'dashboard' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/cms-ir/dashboard',
                    'defaults' => array(
                        'module' => 'CmsIr\Dashboard',
                        'controller' => 'CmsIr\Dashboard\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'layout/layout'  => __DIR__ . '/../view/layout/dashboard.phtml',
            'partial/top-nav'  => __DIR__ . '/../view/partial/dashboard-top-nav.phtml',
            'partial/sidebar-nav'  => __DIR__ . '/../view/partial/dashboard-sidebar-nav.phtml',
            'partial/footer'  => __DIR__ . '/../view/partial/dashboard-footer.phtml',
        ),
        'template_path_stack' => array(
            'dashboard' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
);