<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\System\Controller\System' => 'CmsIr\System\Controller\SystemController'
        ),
    ),
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/status-massive-log-event-modal'  => __DIR__ . '/../view/partial/status-massive-log-event-modal.phtml',
            'partial/flashmessages-system'  => __DIR__ . '/../view/partial/flashmessages-system.phtml',
        ),
        'template_path_stack' => array(
            'system' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Form\FormAbstractServiceFactory',
        ),
        'factories' => array(
            'CmsIr\System\Service\StatusService' => 'CmsIr\System\Service\Factory\StatusService',
            'CmsIr\System\Logger\Logger' => 'CmsIr\System\Logger\Factory\Logger',
            'mail.transport' => 'CmsIr\System\Service\Factory\MailConfigService',
            'CmsIr\System\Service\LanguageService' => 'CmsIr\System\Service\Factory\LanguageService',
            'CmsIr\System\Service\BlockService' => 'CmsIr\System\Service\Factory\BlockService',
        ),
    ),
    'strategies' => array(
        'ViewJsonStrategy',
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables'=> array(
            'customFormHelper' => 'CmsIr\System\View\Helper\FormInput',
            'languageHelper' => 'CmsIr\System\View\Helper\Language',
        ),
    ),
//    'change-access' => array(
//        'path' => './config/autoload/acl.global.php',
//        'pass' => '882b5e09db9b4d7da178b64ed98ecfc3',
//        'access' => 'c4ca4238a0b923820dcc509a6f75849b',
//        'no-access' => 'cfcd208495d565ef66e7dff9f98764da'
//    )
);