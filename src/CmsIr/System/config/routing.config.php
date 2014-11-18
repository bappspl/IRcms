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
);
