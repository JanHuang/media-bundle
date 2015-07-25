<?php 

Routes::group('/dash/media', function () {
    /**
     * url: /dash/media/list
     *
     */
    Routes::get(['/list', 'name' => 'dash_media_list'], 'Media\\Events\\Index@indexAction');
});
