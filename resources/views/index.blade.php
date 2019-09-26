@extends('layouts.app')

@section('content')
    <!-- Jumbotron -->
    <form action="{{route('domains.store')}}" method="POST">
        <div class="jumbotron">
            <h1 class="display-4">@lang('messages.title')</h1>
            <p class="lead">@lang('messages.text')</p>
            @isset($errors)
                @foreach($errors as $error)
                    <div class="alert alert-danger" role="alert">{{ $error }}</div>
                @endforeach
            @endisset
            <div class="form-group">
                <input type="text" name="url" class="form-control" id="testControl" placeholder="Enter webpage URL">
            </div>
            <input class="btn btn-primary btn-lg" type="submit" value="Test" name="submit" /> 
        </div>
    </form>
@endsection 