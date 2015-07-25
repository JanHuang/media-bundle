<?php 

Routes::group('/dash/media', function () {
    /**
     * url: /dash/media/list
     *
     */
    Routes::get(['/list', 'name' => 'dash_media_list'], 'Media\\Events\\Index@indexAction');
});

Routes::group('/api/v1', function () {
    Routes::post(['/uploaded', 'name' => 'upload_api_v1'], 'Media\\Apis\\V1\\Uploader@uploadAction')
        ->setFormats(['json', 'php'])
    ;
});
