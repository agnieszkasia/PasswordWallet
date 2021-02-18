@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h3 mt-2">
                            {{ __('This email does not exist in system or you are the owner of this email') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
