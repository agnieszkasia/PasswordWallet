@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between ">
                        <div>
                            <h3>{{ __('Your activity - function : ') }} {{$function_name}}</h3>
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
                            @foreach($functionActivity as $log)
                                <tr>
                                    <td>{{$log->user_id}}</td>
                                    <td>{{$log->function_id}}</td>
                                    <td>{{$log->function_name}}</td>
                                    <td>{{$log->created_at}}</td>

                                @if($log->function_name == 'delete' )
                                    <td>
                                        <form method="POST" class="mr-2" action="/restore">
                                            @csrf
                                            <input id="data_id" type="hidden" class="form-control" name="data_id" value="{{$log->id}}">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Restore') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
