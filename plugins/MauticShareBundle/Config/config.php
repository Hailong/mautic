<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'name'        => 'Page Share',
    'description' => 'Tracks page sharing.',
    'version'     => '1.0',
    'author'      => 'Hailong',

    'routes' => [
        'main' => [
        ],
        'public' => [
            'mautic_share_on_page_share' => [
                'path'       => '/mtps',
                'controller' => 'MauticShareBundle:Ajax:onShare',
            ],
        ],
    ],

    'services' => [
        'events' => [
            'mautic.share.js.subscriber' => [
                'class'     => 'MauticPlugin\MauticShareBundle\EventListener\BuildJsSubscriber',
                'arguments' => [
                    'templating.helper.assets',
                ],
            ],
        ],
        'models' => [
            'mautic.share.model.share' => [
                'class' => 'MauticPlugin\MauticShareBundle\Model\ShareModel',
            ],
        ],
        'others' => [
        ],
    ],
    'menu' => [
        'main' => [
        ],
    ],

    'categories' => [
    ],

    'parameters' => [
    ],
];
