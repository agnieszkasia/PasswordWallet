<?php

namespace App\Http\Controllers\Auth;
use App\AppKey;
use App\Http\Controllers\UserLogController;
use App\IpLock;
use App\LockedUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class LoginController extends Controller
{
    protected $redirectTo = '/home';

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request){
        $controller = new UserLogController();
        $this->validateLogin($request);
        $user = User::all()->where('name', $request->input('name'))->first();
        if (empty($user)){
            $user = User::all()->where('email', $request->input('name'))->first();
        }

        $user_lock = $controller->userIsLocked($request);
        $user_login = $this->attemptLogin($request);

        if ($controller->checkIfUserHasIpLock($request) == true){

            if($controller->ipIsLocked($request) == true OR $user_login == false){
                if($controller->ipHasTooManyLoginAttempts($request) == true){
                    return $controller->showIpLockTime($request);
                }
            }
        }
        if ($user_lock == true OR $user_login == false){
            $controller->createFail($request);
            if($controller->userHasTooManyLoginAttempts($request) == true){
                return $controller->showLockTime($request);
            }
        }
        elseif ($user_lock == false OR $user_login == true){
            $controller->deleteUserLockout($request);
            Auth::loginUsingId($user->id);
            $controller->createSuccess($request);
            return redirect('/home');

        }
        return Redirect::back()->withErrors('name', 'Password does not match');
    }

    public function attemptLogin(Request $request){
        $user = User::all()->where('name', $request->input('name'))->first();
        $user2 =  User::all()->where('email', $request->input('name'))->first();
        if (empty($user) AND empty($user2)) {
            return false;
        } else {
            $password = $request->input('password');
            if (empty($user)){
                if ($this->checkPassword($user2, $password) == true) {
                    return true;
                }
                else return false;
            }
            else {
                if ($this->checkPassword($user, $password) == true) {
                    return true;
                } else return false;
            }
        }
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'name' => 'string',
            'password' => 'string',
        ]);
    }

    public function checkPassword($user, $password){
        if ($this->checkIfHmac($user) == true) {
            if ($this->checkHmacPassword($password, $user) == true){
                return true;
            }
            else return false;
        } else {
            if ($this->checkSha512Password($password, $user) == true){
                return true;
            }
        }
    }

    public function checkIfHmac($user){
        $code = $user->code;
        if ($code == 'hmac') {
            return true;
        }
        elseif ($code == 'sha512') {
            return false;
        }
    }

    public function checkHmacPassword($password, $user){
        $passwordKey = $user->passwordkey;
        $pass = hash_hmac('sha512', $password, $passwordKey);
        if ($pass == $user->password) {
            return true;
        }
        else return false;
    }

    public function checkSha512Password($password, $user){
        $appKey = AppKey::appKey ;
        $passwordKey = $user->passwordkey;
        $pass = hash("sha512", $password . $passwordKey . $appKey);
        if ($pass == $user->password) {
            return true;
        }
        else return false;
    }
}
