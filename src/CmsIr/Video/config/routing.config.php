<?php
return array(
    'video-main' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/video',
            'defaults' => array(
                'module' => 'CmsIr\Video',
                'controller' => 'CmsIr\Video\Controller\Video',
                'action'     => 'list',
            ),
        ),
    ),
    'video' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/video',
            'defaults' => array(
                'module' => 'CmsIr\Video',
                'controller' => 'CmsIr\Video\Controller\Video',
                'action'     => 'list',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Video',
                        'controller' => 'CmsIr\Video\Controller\Video',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:video_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Video',
                        'controller' => 'CmsIr\Video\Controller\Video',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'video_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:video_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Video',
                        'controller' => 'CmsIr\Video\Controller\Video',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'video_id' => '[0-9]+'
                    ),
                ),
            ),
            'change-status' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-status/:video_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Video',
                        'controller' => 'CmsIr\Video\Controller\Video',
                        'action'     => 'changeStatus',
                    ),
                    'constraints' => array(
                        'video_id' =>  '[0-9]+'
                    ),
                ),
            ),
        ),
    ),
);