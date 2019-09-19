<?php

use Laravel\Lumen\Testing\DatabaseTransactions;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class DomainsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp():void
    {
        parent::setUp();
        $path = 'tests/fixtures/test.html';
        $body = file_get_contents($path);
        $mock = new MockHandler([
            new Response(200, ['Content-Length' => 11], $body)
        ]);
        $handler = HandlerStack::create($mock);
        $this->app->bind('GuzzleClient', function ($app) use ($handler) {
            return new Client(['handler' => $handler]);
        });
    }

    public function testStore()
    {
        $domain = factory('App\Domain')->make();
        $this->post(route('domains.store'), ['domain' => $domain->name]);
        $this->assertResponseStatus(302);
        $this->seeInDatabase('domains', ['name' => 'http://google.com']);
    }

    public function testIndex()
    {
        $this->get(route('domains.index'));
        $this->seeStatusCode(200);
    }

    public function testShow()
    {
        $domain = factory('App\Domain')->make();
        $domain->save();
        $this->get(route('domains.show', ['id' => $domain->id]));
        $this->assertResponseStatus(200);
    }
}
