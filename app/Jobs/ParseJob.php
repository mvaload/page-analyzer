<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class ParseJob extends Job
{
    private $url;
    public function __construct($url)
    {
        $this->url = $url;
    }
    public function handle()
    {
        $client = new Client();
        $response = $client->request('GET', $this->url);
        $body = $response->getBody();
        
        DB::table('domains')
            ->where('name', $this->url)
            ->update(['body' => $body]);
    }
}