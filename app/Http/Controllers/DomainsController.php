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

        $messages = [
            'url.required' => trans('messages.required'),
            'url.unique' => trans('messages.unique')
        ];

        $validator = Validator::make($request->all(), [
            'url' => 'required|unique:domains,name'
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return view('index', ['errors' => $errors]);
        }

        $url = $request->input('url');
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
}
