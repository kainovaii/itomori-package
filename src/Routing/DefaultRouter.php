<?php

use Obsidian\Routing\Router;

/*
|------------------------------------------------
| Default Routes
|------------------------------------------------
*/

Router::get('/api/v1', function () {
    echo json_encode([
        'version' => 'dev',
        'response_time' => '23',
    ]);
});
