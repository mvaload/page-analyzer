<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Jobs\ParseJob;
use DiDom\Document;
use App\Domain;

class DomainsController extends Controller
{
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['url' => 'required|URL']);
        if ($validator->fails()) {
            $urlErrorMessage = 'URL entered incorrectly. Please enter valid URL (example - http://google.com)';
            return redirect()->route('index.show', ['urlErrorMessage' => $urlErrorMessage]);
        }

        $url = $request->input('url');

        if ($this->hasURL($url)) {
            $urlErrorMessage = 'Url is already added to database.';
            return redirect()->route('index.show', ['urlErrorMessage' => $urlErrorMessage]);
        }

        $response = $this->client->request('GET', $url);
        $contentLengthHeader = $response->getHeader('Content-Length');
        $contentLength = isset($contentLengthHeader[0]) ? $contentLengthHeader[0] : 0;
        $responseCode = $response->getStatusCode();

        $id = DB::table('domains')->insertGetId(['name' => $url,
                                                 'created_at' => Carbon::now()->toDateTimeString(),
                                                 'contentLength' => $contentLength,
                                                 'responseCode' => $responseCode,
                                                 'body' => '',
                                                 'h1' => '',
                                                 'keywords' => '',
                                                 'description' => ''
                                                 ]);
        dispatch(new ParseJob($url));
        return redirect()->route('domains.show', ['id' => $id]);
    }
    
    public function show($id)
    {
        $url = Domain::findOrFail($id);
        return view('domains', ['url' => $url]);
    }

    public function index()
    {
        $urls = Domain::paginate(10);
        return view('domainsIndex', ['urls' => $urls]);
    }

    public function hasURL($urlName)
    {
        $url = DB::table('domains')->where('name', $urlName)->first();
        return !empty($url);
    }
}
