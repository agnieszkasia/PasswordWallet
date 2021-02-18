@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="h3 mt-2">{{ __('Password sharing:') }} </div>
                    </div>
                    <div class="card-body ">
                        @foreach($users as $user)
                            <div>User email: {{$user->email}}</div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
