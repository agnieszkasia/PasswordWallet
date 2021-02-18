@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="h3 mt-2">{{ __('Do you want to change to edit mode?') }}</div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('editMode') }}">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input id="passwordkey" type="hidden" class="form-control" name="passwordkey" value="1">
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-3">
                                    <input type="radio" id="yes" name="edit_mode" value="1" checked>
                                    <label for="huey">YES</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="radio" id="no" name="edit_mode" value="0">
                                    <label for="dewey">NO</label>
                                </div>
                            </div>

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
