<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CmsIr\System\Controller\System' => 'CmsIr\System\Controller\SystemController',
            'CmsIr\System\Console\FakeDataCommand' => 'CmsIr\System\Console\FakeDataCommand',
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
        'strategies' => array(
            'ViewJsonStrategy',
        ),
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
    'console' => array(
        'router' => array(
            'routes' => array(
                'fake-data' => array(
                    'options' => array(
                        'route' => 'fake-data',
                        'defaults' => array(
                            'module' => 'CmsIr\System',
                            'controller' => 'CmsIr\System\Console\FakeDataCommand',
                            'action'     => 'createFakeData',
                        ),
                    ),
                ),
            )
        )
    ),
    'doctrine' => array(
        'driver' => array(
            'system_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/System/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'CmsIr\System\Entity' => 'system_driver'
                )
            )
        )
    )
);