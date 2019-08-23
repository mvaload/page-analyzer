@extends('layouts.app')

@section('title', 'Domains')

@section('content')
    <table class="table">
        <thead class="thead-light">
            <tr>
            <th scope="col">id</th>
            <th scope="col">URL</th>
            <th scope="col">...</th>
            <th scope="col">...</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($urls as $url)
                <tr>
                <th scope="row">{{ $url->id }}</th>
                <td><a href="{{ $url->name }}">{{ $url->name }}</a></td>
                <td>Otto</td>
                <td>@mdo</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $urls->links() }}
@endsection 