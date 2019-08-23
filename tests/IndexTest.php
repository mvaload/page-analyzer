<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class IndexTest extends TestCase
{
    public function testIndexPage()
    {
        $this->get('/');
        $this->seeStatusCode(200);
    }
}
