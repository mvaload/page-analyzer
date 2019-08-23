<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Jobs\ParseJob;

class DomainsController extends Controller
{
    private $client;
    
    public function __construct()
    {
        $this->client = new Client();
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
                                                 'body' => '']);
        dispatch(new ParseJob($url));
        return redirect()->route('domain.show', ['id' => $id]);
    }
    
    public function show($id)
    {
        $url = DB::select('select * from domains where id = ?', [$id]);
        return view('domains', ['url' => $url]);
    }

    public function index(Request $request, $page = 1)
    {
        $urls = DB::table('domains')->paginate(4);
        return view('domainsIndex', ['urls' => $urls]);
    }

    public function hasURL($urlName)
    {
        $url = DB::select('select * from domains where name = ?', [$urlName]);
        return !empty($url);
    }
}
