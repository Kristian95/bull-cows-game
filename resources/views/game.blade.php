@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                <form method="POST" class="text-center" action="{{ route('guess') }}">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Guess Number</label>
                        <input type="number" name="number" maxlength="4" pattern="^(?:([0-9])(?!.*\1)){4}$" id="guess" required autocomplete="off">
                    </div>
                    <button class="btn btn-success" type="submit">submit</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
