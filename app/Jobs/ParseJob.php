<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Carbon\Carbon;
use DiDom\Document;

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

        $document = new Document($this->url, true);
        $h1Html = $document->find('h1');
        $h1 = isset($h1Html[0]) ? $h1Html[0]->text() : '' ;
        $metaKeywords = $document->find('meta[name=keywords]');
        $metaDescription = $document->find('meta[name=description]');
        $keywords = isset($metaKeywords[0]) ? $metaKeywords[0]->attr('content') : '' ;
        $description = isset($metaDescription[0]) ? $metaDescription[0]->attr('content') : '' ;
        
        DB::table('domains')
            ->where('name', $this->url)
            ->update(['body' => $body,
                    'updated_at' => Carbon::now()->toDateTimeString(),
                    'h1' => $h1,
                    'keywords' => $keywords,
                    'description' => $description
                    ]);
    }
}
