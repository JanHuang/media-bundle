<?php 

Routes::group('/dash/media', function () {
    /**
     * url: /dash/media/list
     *
     */
    Routes::get(['/list', 'name' => 'media_bundle_list'], 'Media\\Events\\Index@indexAction');
});

Routes::group('/api/v1', function () {
    Routes::post(['/uploaded', 'name' => 'media_upload_v1'], 'Media\\Apis\\V1\\Uploader@uploadAction')
        ->setFormats(['json', 'php'])
    ;
});
