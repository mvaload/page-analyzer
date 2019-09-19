<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Bus\Dispatcher;
use App\Jobs\DomainJob;
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

        $domain = Domain::create(['name' => $url]);

        dispatch(new DomainJob($domain));
        return redirect()->route('domains.show', ['id' => $domain->id]);
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
        $domain = Domain::where('name', $urlName)->first();
        return !empty($domain);
    }
}
