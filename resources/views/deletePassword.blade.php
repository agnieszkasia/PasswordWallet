@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body text-center">
                        <div class=" h3">{{ __('Click Continue to delete the password') }}</div>

                        <form method="POST" action="{{ '/password/delete' }}">
                            @csrf
                            <input id="password_id" type="hidden" class="form-control" name="password_id" value="{{$password_id }}">
                            <div class="form-group ">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Continue') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
