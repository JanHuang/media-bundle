<?php return [
    'uploaded' => [
        'path' => '%root.path%/../public/upload/%date%',
        'exts' => [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/icon'
        ],
        'size' => '10M'
    ],
    /**
     * Configuration
        'media' => [
            'connection' => 'media',
            'repository' => 'Media:Repository:Media',
            'resrouces_url' => null,
            'remote' => [
                'prefix'    => '',
                'url'       => '',
            ],
            'thumbnil' => [
                'width'     => 120,
                'height'    => 120,
            ]
        ],
     */

];
