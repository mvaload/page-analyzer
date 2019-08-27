<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Http\Controllers\DomainsController;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use App\Post;

class DomainsTest extends TestCase
{
    use DatabaseTransactions;

    public function testStore()
    {
        $mockGuzzle = new MockHandler([
            new Response(200, ['Content-Length' => 0,
                                'body' => '<h1>Tag</h1>',
                                'description' => 'description smarty',
                                'keywords' => 'keywords smarty']),
            new Response(200, ['body' => '<h1>Tag</h1>',
                               'description' => 'description smarty',
                               'keywords' => 'keywords smarty'])
        ]);
        $handler = HandlerStack::create($mockGuzzle);
        $guzzle = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $guzzle);

        $this->post(route('domains.store'), ['url' => 'https://www.smarty.net']);
        $this->seeStatusCode(302);
        $this->seeInDatabase('domains', ['name' => 'https://www.smarty.net']);
    }
    public function testIndex()
    {
        $this->get(route('domains.index'));
        $this->seeStatusCode(200);
    }
    public function testShow()
    {
        $this->post(route('domains.store'), ['url' => 'https://www.smarty.net']);
        $this->get(route('domains.show', ['id' => 1]));
        $this->seeInDatabase('domains', ['name' => 'https://www.smarty.net',
                                         'h1' => 'Get Smarty',
                                         'description' => 'Smarty is a template engine for PHP.',
                                         'keywords' => 'smarty, template, engine, php']);
    }
}
