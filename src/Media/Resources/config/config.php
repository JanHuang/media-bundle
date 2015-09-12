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


     */
    'media' => [
        'connection' => 'media',
        'repository' => 'Media:Repository:Media',
        'remote' => 'http://localhost/demo/file_demo/upload.php',
        'resrouces_url' => null,
        'thumbnil' => [
            'width' => 120,
            'height' => 120,
        ]
    ],
];
