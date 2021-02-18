@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit password') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('editMainPassword') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="old_password" class="col-md-4 col-form-label text-md-right">{{ __('Old password') }}</label>

                                <div class="col-md-6">
                                    <input id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password">

                                    @error('old_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input id="passwordkey" type="hidden" class="form-control" name="passwordkey" value="1">
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-3">
                                    <input type="radio" id="hmac" name="code" value="hmac" checked>
                                    <label for="huey">HMAC</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="radio" id="sha512" name="code" value="sha512">
                                    <label for="dewey">SHA512</label>
                                </div>
                            </div>
                            <input id="user_id" type="hidden" class="form-control" name="user_id" value="{{Auth::user()->id}}">
                            <input id="password_id" type="hidden" class="form-control" name="password_id" value="{{Auth::user()->id}}">

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save') }}
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
