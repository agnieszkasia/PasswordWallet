<?php

namespace App\Http\Controllers\Auth;
use App\AppKey;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    use AuthenticatesUsers;

    public function showRegistrationForm(){
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data= Controller::encryptPassword($request);
        $request['password'] = $data['password'];
        $request['passwordkey'] = $data['passwordkey'];

        event(new Registered($user = $this->create($request->all())));

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect('/login');
    }

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct(){
        $this->middleware('guest');
    }

    protected function validator(array $data){
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique'],
            'password' => ['required', 'string', 'min:4', 'confirmed']
        ]);
    }

    public function create(array $data){
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'passwordkey' => $data['passwordkey'],
            'code' => $data['code'],
            'lock' => null
        ]);
    }
}
