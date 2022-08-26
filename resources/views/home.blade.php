@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                <form method="GET" class="text-center" action="{{ route('game') }}">
                    <button class="btn btn-success" type="submit">Start</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
