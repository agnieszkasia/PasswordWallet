<?php

namespace App\Http\Controllers;
use App\AppKey;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(){
        $this->middleware('auth');
    }

    public function generateSalt() {
        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/\\][{}\'";:?.>,<!@#$%^&*()-_=+|';
        $randString = "";
        $randStringLen = 16;

        while(strlen($randString) < $randStringLen) {
            $randChar = substr(str_shuffle($charset), mt_rand(0, strlen($charset)), 1);
            $randString .= $randChar;
        }
        return $randString;
    }

    public function encryptPassword($request){
        $data['passwordkey'] = Controller::generateSalt();

        $appKey = AppKey::appKey ;
        if ($request['code'] == 'hmac'){
            $data['password'] = hash_hmac('sha512', $request['password'], $data['passwordkey']);
        }
        else if ($request['code'] == 'sha512'){
            $data['password'] = hash("sha512", $request['password'].$data['passwordkey'].$appKey);
        }

        return $data;
    }
}
