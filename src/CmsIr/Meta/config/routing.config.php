<?php
return array(
    'meta-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/meta',
            'defaults' => array(
                'module' => 'CmsIr\Meta',
                'controller' => 'CmsIr\Meta\Controller\Meta',
                'action'     => 'list',
            ),
        ),
    ),
    'meta' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/meta',
            'defaults' => array(
                'module' => 'CmsIr\Meta',
                'controller' => 'CmsIr\Meta\Controller\Meta',
                'action'     => 'list',
            ),
        ),
        'child_routes' => array(
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:meta_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Meta',
                        'controller' => 'CmsIr\Meta\Controller\Meta',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'meta_id' => '[0-9]+'
                    ),
                ),
            ),
        ),
    ),
);