@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h3 mt-2">
                            {{ __('You have to be an owner of this password') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
