@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h3>{{ __('Your logs') }}</h3>
                        </div>
                        <div>
                            <a href="{{ '/loginSettings' }}" class="btn btn-primary"> Login settings</a>

                        </div>
                    </div>
                    <div class="card-body ">
                        <table class="col-md-12">
                            <tbody>
                            <tr class="border-bottom">
                                <td><div class="col-form-label">User ID </div></td>
                                <td><div class=" col-form-label">Login </div></td>
                                <td><div class="col-form-label">Address IP</div></td>
                                <td><div class="col-form-label">Last log time </div></td>
                                <td><div class="col-form-label">Status </div></td>
                                <td><div class="col-form-label">Number of attempt</div></td>
                            </tr>
                            @foreach($failLogData as $log)
                            <tr>
                                <td>{{$log->user_id}}</td>
                                <td>{{$log->name}}</td>
                                <td>{{$log->ip_address}}</td>
                                <td>{{$log->time}}</td>
                                <td>{{$log->status}}</td>
                                <td>{{$log->attempts}}</td>
                            </tr>
                                @endforeach
                            @foreach($successLogData as $log)
                                <tr>
                                    <td>{{$log->user_id}}</td>
                                    <td>{{$log->name}}</td>
                                    <td>{{$log->ip_address}}</td>
                                    <td>{{$log->time}}</td>
                                    <td>{{$log->status}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-5">
                            @if($lock == '0')
                                IP checking is off
                            @elseif ($lock == 1 or $lock == null)
                            IP checking is on
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
