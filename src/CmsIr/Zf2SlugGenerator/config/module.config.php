<?php

/**
 * Config for the slug generator
 */
return array(
    'service_manager' => array(
        'factories' => array(
            'CmsIr\Zf2SlugGenerator\SlugService' => 'CmsIr\Zf2SlugGenerator\Service\Factory\SlugFactory',
        ),
        'invokables' => array(
            'CmsIr\Zf2SlugGenerator\Mapper\DbTable' => 'CmsIr\Zf2SlugGenerator\Mapper\DbTable',
            'CmsIr\Zf2SlugGenerator\Entity\Result' => 'CmsIr\Zf2SlugGenerator\Entity\Result',
        ),
    ),
);
