@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Share password') }}</div>

                    <div class="card-body ">
                        <form method="POST" action="{{ route('sharePassword') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Share with email') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control @error('login') is-invalid @enderror" name="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <input id="password" type="hidden" class="form-control" name="password" value="{{$password}}">
                            <input id="password_id" type="hidden" class="form-control" name="password_id" value="{{$password_id}}">

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Share') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
