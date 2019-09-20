<?php

namespace App\Jobs;

use DiDom\Document;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Domain;

class DomainJob extends Job
{
    private $domain;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function handle(Client $client)
    {
        
        $response = $client->get($this->domain->name);
       
        $document = $this->parse();
        $this->domain->created_at = Carbon::now()->toDateTimeString();
        $this->domain->contentLength = isset($response->getHeader('Content-Length')[0]) ?
                                            $response->getHeader('Content-Length')[0] : 'unknown';
        $this->domain->responseCode = $response->getStatusCode();
        $this->domain->body = $response->getBody()->getContents();
        $this->domain->h1 = $document->has('h1') ? $document->first('h1')->text() : 'undefined';
        $this->domain->keywords = $document->has('meta[name=keywords]') ?
                                $document->find('meta[name=keywords]')[0]->attr('content') : 'undefined';
        $this->domain->description = $document->has('meta[name=description]') ?
                                    $document->find('meta[name=description]')[0]->attr('content') : 'undefined';
        $this->domain->save();
    }

    public function parse()
    {
        return new Document($this->domain['name'], true);
    }
}
