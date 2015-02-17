<?php
return array(
    'newsletter-main' => array(
        'may_terminate' => true,
        'type'    => 'Literal',
        'options' => array(
            'route'    => '/cms-ir/newsletter',
            'defaults' => array(
                'module' => 'CmsIr\Newsletter',
                'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                'action' => 'newsletter',
            ),
        ),
    ),
    'newsletter' => array(
        'may_terminate' => true,
        'type'    => 'Literal',
        'options' => array(
            'route'    => '/cms-ir/newsletter',
            'defaults' => array(
                'module' => 'CmsIr\Newsletter',
                'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                'action'     => 'newsletter',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create-newsletter',
                    'defaults' => array(
                        'module' => 'CmsIr\Newsletter',
                        'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                        'action' => 'createNewsletter',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit-newsletter/:newsletter_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Newsletter',
                        'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                        'action' => 'editNewsletter',
                    ),
                    'constraints' => array(
                        'newsletter_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete-newsletter/:newsletter_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Newsletter',
                        'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                        'action' => 'deleteNewsletter',
                    ),
                    'constraints' => array(
                        'newsletter_id' => '[0-9]+'
                    ),
                ),
            ),
            'preview' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/preview-newsletter/:newsletter_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Newsletter',
                        'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                        'action' => 'previewNewsletter',
                    ),
                    'constraints' => array(
                        'newsletter_id' => '[0-9]+'
                    ),
                ),
            ),
            'send' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/send-newsletter/:newsletter_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Newsletter',
                        'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                        'action' => 'sendNewsletter',
                    ),
                    'constraints' => array(
                        'newsletter_id' => '[0-9]+'
                    ),
                ),
            ),
            'settings' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/newsletter-settings',
                    'defaults' => array(
                        'module' => 'CmsIr\Newsletter',
                        'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                        'action'     => 'newsletterSettings',
                    ),
                ),
            ),
            'upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'CmsIr\Users',
                        'controller' => 'CmsIr\Users\Controller\Users',
                        'action'     => 'upload',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'subscriber-group' => array(
                'type'    => 'Segment',
                'may_terminate' => true,
                'options' => array(
                    'route'    => '/subscriber-group',
                    'defaults' => array(
                        'module' => 'CmsIr\Newsletter',
                        'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                        'action'     => 'subscriberGroup',
                    ),
                ),
                'child_routes' => array(
                    'create' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/create-group',
                            'defaults' => array(
                                'module' => 'CmsIr\Newsletter',
                                'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                                'action' => 'createSubscriberGroup',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit-group/:subscriber_group_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Newsletter',
                                'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                                'action' => 'editSubscriberGroup',
                            ),

                            'constraints' => array(
                                'subscriber_group_id' => '[0-9]+'
                            ),
                        ),
                    ),
                    'delete' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/delete-group/:subscriber_group_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Newsletter',
                                'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                                'action' => 'deleteSubscriberGroup',
                            ),

                            'constraints' => array(
                                'subscriber_group_id' => '[0-9]+'
                            ),
                        ),
                    ),
                    'preview' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/preview-group/:subscriber_group_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Newsletter',
                                'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                                'action' => 'previewSubscriberGroup',
                            ),

                            'constraints' => array(
                                'subscriber_group_id' => '[0-9]+'
                            ),
                        ),
                    ),
                ),
            ),
            'subscriber-list' => array(
                'type'    => 'Segment',
                'may_terminate' => true,
                'options' => array(
                    'route'    => '/subscriber-list',
                    'defaults' => array(
                        'module' => 'CmsIr\Newsletter',
                        'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                        'action'     => 'subscriberList',
                    ),
                ),
                'child_routes' => array(
                    'create' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/create-subscriber',
                            'defaults' => array(
                                'module' => 'CmsIr\Newsletter',
                                'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                                'action' => 'createSubscriber',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit-subscriber/:subscriber_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Newsletter',
                                'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                                'action' => 'editSubscriber',
                            ),

                            'constraints' => array(
                                'subscriber_id' => '[0-9]+'
                            ),
                        ),
                    ),
                    'preview' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/preview-subscriber/:subscriber_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Newsletter',
                                'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                                'action' => 'previewSubscriber',
                            ),

                            'constraints' => array(
                                'subscriber_id' => '[0-9]+'
                            ),
                        ),
                    ),
                    'delete' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/delete-subscriber/:subscriber_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Newsletter',
                                'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                                'action' => 'deleteSubscriber',
                            ),

                            'constraints' => array(
                                'subscriber_id' => '[0-9]+'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);