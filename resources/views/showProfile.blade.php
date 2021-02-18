@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="h3 mt-2">{{ __('Profile') }}</div>

                    </div>
                    <div class="card-body ">
                        <div class="d-flex form-group">
                            <div class="col-md-2 col-form-label text-md-right">Name: </div>
                            <input class="form-control col-md-4" value="{{$user->name}}">
                        </div>
                        <div class="d-flex form-group">
                            <div class="col-md-2 col-form-label text-md-right">Email: </div>
                            <input class="form-control col-md-4" value="{{$user->email}}">
                        </div>
                        <div class="d-flex form-group">
                            <div class="col-md-2 col-form-label text-md-right">Password: </div>
                            <input class="form-control col-md-4" value="***">

                            <a class="btn-primary btn ml-2" href="{{ route('editMainPassword') }}">
                                {{ __('Edit') }}
                            </a>
                        </div>
                        <div class="d-flex form-group">
                            <div class="col-md-2 col-form-label text-md-right">Mode: </div>
                            <input class="form-control col-md-4" value="{{$user->edit_mode}}">
                            <a class="btn-primary btn ml-2" href="{{ route('showEditModeForm') }}">
                                {{ __('Edit') }}
                            </a>
                        </div>
                        <div class="d-flex form-group">
                            <div class="col-md-2 col-form-label text-md-right">IP checking: </div>
                            <input class="form-control col-md-4" value="{{$user->ip_lock}}">
                            <a href="{{ '/loginSettings' }}" class="btn btn-primary ml-2">
                                {{ __('Edit') }}
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
