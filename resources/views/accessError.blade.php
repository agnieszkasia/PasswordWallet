@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header">
                        <div class="h3 mt-2">
                            {{ __('You have to switch to the modify mode to edit mode') }}
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('addPassword') }}">
                            @csrf


                            <input id="user_id" type="hidden" class="form-control" name="user_id" value="{{Auth::user()->id }}">
                            <input id="passwordkey" type="hidden" class="form-control" name="passwordkey" value="{{Auth::user()->passwordkey }}">

                            <div class="form-group row mb-0 justify-content-center">
                                <div class=" d-flex">
                                    <a href="{{ '/showAllPasswords' }}" class="btn btn-primary mr-2"> Back</a>
                                    <a href="{{ '/profile' }}" class="btn btn-primary"> Go to profile settings</a>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
