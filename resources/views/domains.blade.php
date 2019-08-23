@extends('layouts.app')

@section('title', 'Domains')

@section('content')
    <table class="table">
        <thead class="thead-light">
            <tr>
            <th scope="col">id</th>
            <th scope="col">URL</th>
            <th scope="col">Response Code</th>
            <th scope="col">content-length</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th scope="row">{{ $url[0]->id }}</th>
            <td>{{ $url[0]->name }}</td>
            <td>{{ $url[0]->responseCode }}</td>
            <td>{{ $url[0]->contentLength }}</td>
            </tr>
        </tbody>
    </table>
@endsection 