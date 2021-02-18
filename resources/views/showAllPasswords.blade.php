@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="h3 mt-2">{{ __('Your passwords') }} </div>
                        <div>
                            <a href="{{ '/addPassword' }}" class="btn btn-primary"> Add password</a>

                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @foreach($password as $item)
                            <div class="d-flex justify-content-between border-bottom mb-3">
                                <div>
                                    <div class="mt-3 h5">{{$item->web_address}}</div>
                                </div>

                                <div class="mb-3 d-flex dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Options
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <form method="POST" class="mr-2" action="/password/{{$item->id}}/share">
                                                @csrf

                                                <input id="password_id" type="hidden" class="form-control" name="password_id" value="{{$item->id}}">
                                                <input id="passwordkey" type="hidden" class="form-control" name="passwordkey" value="{{Auth::user()->passwordkey }}">
                                                <button type="submit" class="dropdown-item">
                                                    {{ __('Share') }}
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" class="mr-2" action="/password/{{$item->id}}/sharedFor">
                                                @csrf

                                                <input id="password_id" type="hidden" class="form-control" name="password_id" value="{{$item->id}}">
                                                <button type="submit" class="dropdown-item">
                                                    {{ __('Shared for') }}
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" action="/password/{{$item->id}}">
                                                @csrf

                                                <input id="password_id" type="hidden" class="form-control" name="password_id" value="{{$item->id}}">
                                                <input id="passwordkey" type="hidden" class="form-control" name="passwordkey" value="{{Auth::user()->passwordkey }}">
                                                <button type="submit" class="dropdown-item">
                                                    {{ __('Show details') }}
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" class="mr-2" action="/password/{{$item->id}}/edit">
                                                @csrf

                                                <input id="password_id" type="hidden" class="form-control" name="password_id" value="{{$item->id}}">
                                                <input id="passwordkey" type="hidden" class="form-control" name="passwordkey" value="{{Auth::user()->passwordkey }}">

                                                <button type="submit" class="dropdown-item">
                                                    {{ __('Edit') }}
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" class="mr-2" action="/password/{{$item->id}}/delete">
                                                @csrf

                                                <input id="password_id" type="hidden" class="form-control" name="password_id" value="{{$item->id}}">
                                                <button type="submit" class="dropdown-item">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <div class="card mt-5">
            <div class="card-header d-flex justify-content-between ">
                <div>
                    <h3>{{ __('Your activity') }}</h3>
                </div>
                <div>
                    <a href="{{ '/functionCreate' }}" class="btn btn-primary"> Function CREATE</a>

                </div>
                <div>
                    <a href="{{ '/functionUpdate' }}" class="btn btn-primary"> Function UPDATE</a>

                </div>
                <div>
                    <a href="{{ '/functionDelete' }}" class="btn btn-primary"> Function DELETE</a>

                </div>
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
                            {{--@if($data->function_name == 'delete' )--}}
                            {{--<td>--}}
                            {{--<form method="POST" class="mr-2" action="/restore">--}}
                            {{--@csrf--}}
                            {{--<input id="data_id" type="hidden" class="form-control" name="data_id" value="{{$data->id}}">--}}
                            {{--<button type="submit" class="btn btn-primary">--}}
                            {{--{{ __('Restore') }}--}}
                            {{--</button>--}}
                            {{--</form>--}}
                            {{--</td>--}}
                            {{--@endif--}}

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>




        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between">
                <div class="h3 mt-2">{{ __('Shared for you') }}</div>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @foreach($sharedPassword as $item)
                    <div class="d-flex justify-content-between border-bottom mb-3">
                        <div>
                            <div class="mt-3 h5">{{$item->web_address}}</div>
                        </div>
                        <div class="mb-3 d-flex">

                            <form method="POST" action="/password/{{$item->id}}">
                                @csrf

                                <input id="password_id" type="hidden" class="form-control" name="password_id" value="{{$item->password_id}}">
                                <input id="passwordkey" type="hidden" class="form-control" name="passwordkey" value="{{Auth::user()->passwordkey }}">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Show details') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
