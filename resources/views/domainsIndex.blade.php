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
            @foreach ($urls as $url)
                <tr>
                <th scope="row">{{ $url->id }}</th>
                <td><a href="{{ $url->name }}">{{ $url->name }}</a></td>
                <td>{{ $url->responseCode }}</td>
                <td>{{ $url->contentLength }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $urls->links() }}
@endsection 