<?php

namespace App;

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Request;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $uri = $app->make('config')->get('app.url', 'http://localhost');
        $components = parse_url($uri);
        $server = $_SERVER;
        if (isset($components['path'])) {
            $server = array_merge($server, [
                'SCRIPT_FILENAME' => $components['path'],
                'SCRIPT_NAME' => $components['path'],
            ]);
        }
        $app->instance('request', Request::create($uri, 'GET', [], [], [], $server));
        return $app;
    }
}
