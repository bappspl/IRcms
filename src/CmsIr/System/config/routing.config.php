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
);
