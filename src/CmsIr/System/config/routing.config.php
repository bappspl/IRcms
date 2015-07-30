<?php
return array(
    'system-upload' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/system-upload',
            'defaults' => array(
                'module' => 'CmsIr\System',
                'controller' => 'CmsIr\System\Controller\System',
                'action'     => 'saveEditorImages',
            ),
        ),
    ),
    'thumb' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/thumb/:entity/:size/:filename',
            'defaults' => array(
                'module' => 'CmsIr\System',
                'controller' => 'CmsIr\System\Controller\System',
                'action'     => 'createThumb',
            ),
            'constraints' => array(
                'entity' => '[a-zA-Z0-9_-]+',
                'size' => '[a-zA-Z0-9_-]+',
                'filename' => '[a-zA-Z0-9_.-]+',
            ),
        ),
    ),
    'change-access' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/change-access/:pass/:access',
            'defaults' => array(
                'module' => 'CmsIr\System',
                'controller' => 'CmsIr\System\Controller\System',
                'action'     => 'changeAccess',
            ),
        ),
    ),
    'mail-config' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/mail-config',
            'defaults' => array(
                'module' => 'CmsIr\System',
                'controller' => 'CmsIr\System\Controller\System',
                'action'     => 'mailConfig',
            ),
        ),
    ),
    'log-event' => array(
        'type'    => 'Segment',
        'may_terminate' => true,
        'options' => array(
            'route'    => '/cms-ir/log-event',
            'defaults' => array(
                'module' => 'CmsIr\System',
                'controller' => 'CmsIr\System\Controller\System',
                'action'     => 'logEvent',
            ),
        ),
        'child_routes' => array(
            'change-status' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-status/:id',
                    'defaults' => array(
                        'module' => 'CmsIr\System',
                        'controller' => 'CmsIr\System\Controller\System',
                        'action'     => 'changeStatus',
                    ),
                    'constraints' => array(
                        'id' =>  '[0-9]+'
                    ),
                ),
            ),
        ),
    )
);
