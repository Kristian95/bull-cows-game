@extends('layouts.app')

@section('content')
<div class="container">
    @if (! $errors->isEmpty())
        <div class="alert alert-danger" role="alert">
            {{ $errors->first('number') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                <p class="text-center">Enter four digit unique number</p>
                <form method="POST" class="text-center" action="{{ route('guess') }}">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Guess Number</label>
                        <input type="number" name="number" required autocomplete="off">
                    </div>
                    <button class="btn btn-success" type="submit">submit</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    @if ($topTenPlayers)
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    Top 10
                    @foreach ($topTenPlayers as $player)
                        <p>{{ $player->name }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (session()->has('bulls') && session()->has('cows'))
        <div class="alert alert-success" role="alert">
            Bulls: {{session()->get('bulls') }} Cows: {{session()->get('cows') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            Win! Attempts count: {{ session()->get('attempts') }}
        </div>
    @endif
</div>
@endsection
