@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            {{ __('Login') }}
                        </div>
                    </div>
                    <div class="card-body ">
                        <div>Too many login attempts</div>
                        <div>Login: {{$name}}</div>
                        <div>IP: {{$ip}}</div>
                        <div>Locked for: {{$lockTime}}</div>
                        <div>Attempts: {{$attempts}}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
