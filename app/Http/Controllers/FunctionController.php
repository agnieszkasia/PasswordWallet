<?php

namespace App\Http\Controllers;

use App\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FunctionController extends Controller
{
    public function showFunction(){

        $user_id = auth()->user()->id;
        $function_name =  ltrim("{$_SERVER['REQUEST_URI']}", '/function');

//        dd($function_name);
        $functionActivity = DB::select("SELECT user_id, function_id, function_name, created_at, a.id
                                      FROM data_changes a 
                                      INNER JOIN user_functions f 
                                      ON f.id = a.function_id 
                                      WHERE user_id = '$user_id'
                                      AND function_name = '$function_name'
                                      ORDER BY created_at
                                      ");
//dd($functionActivity);

//        $password = Password::all()->find($functionActivity['id']);

        return view('functionActivity', compact('function_name', 'functionActivity'));
    }
}
