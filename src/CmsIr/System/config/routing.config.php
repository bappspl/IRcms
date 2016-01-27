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
//            'constraints' => array(
//                'entity' => '[a-zA-Z0-9_-]+',
//                'size' => '[a-zA-Z0-9_-]+',
//                'filename' => '[a-zA-Z0-9_.-]+',
//            ),
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
    'send-test-email' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/send-test-email',
            'defaults' => array(
                'module' => 'CmsIr\System',
                'controller' => 'CmsIr\System\Controller\System',
                'action'     => 'sendTestEmail',
            ),
        ),
    ),
);
