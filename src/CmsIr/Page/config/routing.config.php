<?php
return array(
    'page-main' => array(
        'may_terminate' => true,
        'type' => 'Literal',
        'options' => array(
            'route' => '/cms-ir/page',
            'defaults' => array(
                'module' => 'CmsIr\Page',
                'controller' => 'CmsIr\Page\Controller\Page',
                'action' => 'list',
            ),
        ),
    ),
    'page' => array(
        'may_terminate' => true,
        'type' => 'Literal',
        'options' => array(
            'route' => '/cms-ir/page',
            'defaults' => array(
                'module' => 'CmsIr\Page',
                'controller' => 'CmsIr\Page\Controller\Page',
                'action' => 'list',
            ),
        ),
        'child_routes' => array(
            'create' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action' => 'create',
                    ),
                ),
            ),
            'edit' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:page_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action' => 'edit',
                    ),
                    'constraints' => array(
                        'page_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:page_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action' => 'delete',
                    ),
                    'constraints' => array(
                        'page_id' => '[0-9]+'
                    ),
                ),
            ),
            'upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action'     => 'uploadFiles',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'upload-main' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload-main',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action'     => 'uploadFilesMain',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'delete-photo' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete-photo',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action'     => 'deletePhoto',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'delete-photo-main' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete-photo-main',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action'     => 'deletePhotoMain',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'change-status' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/change-status/:page_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action'     => 'changeStatus',
                    ),
                    'constraints' => array(
                        'page_id' =>  '[0-9]+'
                    ),
                ),
            ),
            'upload-part' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload-part',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action'     => 'uploadFilesPart',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'upload-main-part' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/upload-main-part',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action'     => 'uploadFilesMainPart',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'get-parts' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/get-parts',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action'     => 'getParts',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'order-parts' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/order-parts',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action'     => 'orderParts',
                    ),
                    'constraints' => array(
                    ),
                ),
            ),
            'part' => array(
                'may_terminate' => true,
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/part/:page_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Page',
                        'controller' => 'CmsIr\Page\Controller\Page',
                        'action'     => 'part',
                    ),
                    'constraints' => array(
                        'page_id' =>  '[0-9]+'
                    ),
                ),
                'child_routes' => array(
                    'create' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/create',
                            'defaults' => array(
                                'module' => 'CmsIr\Page',
                                'controller' => 'CmsIr\Page\Controller\Page',
                                'action' => 'createPart',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit/:page_part_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Page',
                                'controller' => 'CmsIr\Page\Controller\Page',
                                'action' => 'editPart',
                            ),
                            'constraints' => array(
                                'page_part_id' => '[0-9]+'
                            ),
                        ),
                    ),
                    'delete' => array(
                        'may_terminate' => true,
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/delete/:page_part_id',
                            'defaults' => array(
                                'module' => 'CmsIr\Page',
                                'controller' => 'CmsIr\Page\Controller\Page',
                                'action' => 'deletePart',
                            ),
                            'constraints' => array(
                                'page_part_id' => '[0-9]+'
                            ),
                        ),
                    ),
                    'change-position' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/change-position',
                            'defaults' => array(
                                'module' => 'CmsIr\Page',
                                'controller' => 'CmsIr\Page\Controller\Page',
                                'action'     => 'changePosition',
                            ),
                            'constraints' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
