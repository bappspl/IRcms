<?php
return array(
    'fake-newsletter' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/newsletter',
            'defaults' => array(
            ),
        ),
    ),
    'newsletter' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/newsletter',
            'defaults' => array(
                'module' => 'CmsIr\Newsletter',
                'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                'action'     => 'newsletter',
            ),
        ),
    ),
    'create-newsletter' => array(
        'may_terminate' => true,
        'type' => 'Segment',
        'options' => array(
            'route' => '/cms-ir/newsletter/create-newsletter',
            'defaults' => array(
                'module' => 'CmsIr\Newsletter',
                'controller' => 'CmsIr\Newsletter\Controller\Newsletter',
                'action' => 'createNewsletter',
            ),
        ),
    ),
    'edit-newsletter' => array(
        'may_terminate' => true,
        'type' => 'Segment',
        'options' => array(
            'route' => '/cms-ir/newsletter/edit-newsletter/:newsletter_id',
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
    'delete-newsletter' => array(
        'may_terminate' => true,
        'type' => 'Segment',
        'options' => array(
            'route' => '/cms-ir/newsletter/delete-newsletter/:newsletter_id',
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
    'preview-newsletter' => array(
        'may_terminate' => true,
        'type' => 'Segment',
        'options' => array(
            'route' => '/cms-ir/newsletter/preview-newsletter/:newsletter_id',
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
    'send-newsletter' => array(
        'may_terminate' => true,
        'type' => 'Segment',
        'options' => array(
            'route' => '/cms-ir/newsletter/send-newsletter/:newsletter_id',
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
    'subscriber-list' => array(
        'type'    => 'Segment',
        'may_terminate' => true,
        'options' => array(
            'route'    => '/cms-ir/newsletter/subscriber-list',
            'defaults' => array(
                'module' => 'CmsIr\Newsletter',
                'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                'action'     => 'subscriberList',
            ),
        ),
        'child_routes' => array(
            'create-subscriber' => array(
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
            'edit-subscriber' => array(
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
            'preview-subscriber' => array(
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
            'delete-subscriber' => array(
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
    'subscriber-group' => array(
        'type'    => 'Segment',
        'may_terminate' => true,
        'options' => array(
            'route'    => '/cms-ir/newsletter/subscriber-group',
            'defaults' => array(
                'module' => 'CmsIr\Newsletter',
                'controller' => 'CmsIr\Newsletter\Controller\Subscriber',
                'action'     => 'subscriberGroup',
            ),
        ),
        'child_routes' => array(
            'create-group' => array(
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
            'edit-group' => array(
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
            'delete-group' => array(
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
            'preview-group' => array(
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
    'newsletter-settings' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/newsletter/newsletter-settings',
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
            'route'    => '/cms-ir/users/upload',
            'defaults' => array(
                'module' => 'CmsIr\Users',
                'controller' => 'CmsIr\Users\Controller\Users',
                'action'     => 'upload',
            ),
            'constraints' => array(
            ),
        ),
    ),
);