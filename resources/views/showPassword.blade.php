@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="h3 mt-2">{{ __('Password data') }}</div>
                        @if($passwordData->user_id == auth()->user()->getAuthIdentifier())
                            <div class="d-flex mt-1">
                                <div class="mr-2">
                                    <form method="POST" class="mr-2" action="/password/{{$passwordData->id}}/edit">
                                        @csrf

                                        <input id="password_id" type="hidden" class="form-control" name="password_id" value="{{$passwordData->id}}">
                                        <input id="passwordkey" type="hidden" class="form-control" name="passwordkey" value="{{Auth::user()->passwordkey }}">

                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Edit') }}
                                        </button>
                                    </form>
                                </div><div>
                                    <form method="POST" class="mr-2" action="/password/{{$passwordData->id}}/delete">
                                        @csrf

                                        <input id="password_id" type="hidden" class="form-control" name="password_id" value="{{$passwordData->id}}">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                        @endif
                    </div>
                    <div class="card-body ">
                        <div class="d-flex form-group">
                            <div class="col-md-2 col-form-label text-md-right">Address: </div>
                            <input class="form-control col-md-4" value="{{$passwordData->web_address}}">
                        </div>
                        <div class="d-flex form-group">
                            <div class="col-md-2 col-form-label text-md-right">Login: </div>
                            <input class="form-control col-md-4" value="{{$passwordData->login}}">
                        </div>
                        <div class="d-flex form-group">
                            <div class="col-md-2 col-form-label text-md-right">Password: </div>
                            <input class="form-control col-md-4" value="{{$passwordData->password}}">
                        </div>
                        <div class="d-flex form-group">
                            <div class="col-md-2 col-form-label text-md-right">Description: </div>
                            <input class="form-control col-md-4" value="{{$passwordData->description}}">
                        </div>
                        <div class="float-right">
                            <a href="{{ '/home' }}" class="btn btn-primary"> Back</a>

                        </div>

                    </div>

                </div>

                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between">
                        <div class="h3 mt-2">{{ __('Password history') }}</div>
                    </div>
                    <div class="card-body ">
                        <table class="col-md-12">
                            <tbody>
                            <tr class="border-bottom">
                                <td><div class="col-form-label">User ID </div></td>
                                <td><div class=" col-form-label">Function ID </div></td>
                                <td><div class=" col-form-label">Function name </div></td>
                                <td><div class="col-form-label">Time</div></td>
                                <td><div class="col-form-label"></div></td>
                            </tr>

                            @foreach($userActivity as $data)
                                <tr>
                                    <td>{{$data->user_id}}</td>
                                    <td>{{$data->function_id}}</td>
                                    <td>{{$data->function_name}}</td>
                                    <td>{{$data->created_at}}</td>
                                    @if($data->function_name == 'update' )
                                        <td>
                                            <form method="POST" class="mr-2" action="/restore">
                                                @csrf
                                                <input id="data_id" type="hidden" class="form-control" name="data_id" value="{{$data->id}}">
                                                <button type="submit" class="btn btn-primary">
                                                {{ __('Restore') }}
                                                </button>
                                            </form>
                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
