<?php

namespace App\Http\Controllers;
use App\IpLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $user_id = auth()->user()->id;
        $password = DB::select("SELECT * FROM passwords WHERE user_id = '$user_id' AND deleted = '0'");

        $failLogData = DB::select("SELECT *
                                      FROM failed_login_attempts
                                      WHERE user_id = '$user_id'
                                      AND status = 'fail'
                                      AND time = (
                                          SELECT MAX(time)
                                          FROM failed_login_attempts
                                          WHERE user_id = '$user_id'
                                          AND status = 'fail'
                                          GROUP BY user_id )
                                      ");
        $successLogData = DB::select("SELECT *
                                      FROM failed_login_attempts
                                      WHERE user_id = '$user_id'
                                      AND status = 'success'
                                      AND time = (
                                          SELECT MAX(time)
                                          FROM failed_login_attempts
                                          WHERE user_id = '$user_id'
                                          AND status = 'success'
                                          GROUP BY user_id )
                                      ");
        $lockData = IpLock::all()->where('login', auth()->user()->name)->first();
        if ($lockData = 'null'){
            $lock = '1';
        } else {
            $lock = $lockData['lock'];

        }

        $userActivity = DB::select("SELECT user_id, function_id, function_name, created_at, a.id
                                      FROM data_changes a
                                      INNER JOIN user_functions f
                                      ON f.id = a.function_id
                                      WHERE user_id = '$user_id'
                                      ORDER BY created_at
                                      ");


        return view('home', compact('password','failLogData', 'successLogData', 'lock', 'userActivity'));
    }
}
