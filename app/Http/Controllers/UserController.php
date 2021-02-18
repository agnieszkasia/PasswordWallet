<?php

namespace App\Http\Controllers;

use App\AppKey;
use App\Password;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function showEditPasswordForm(){
        return view('editMainPassword');
    }

    public function showEditModeForm(){
        return view('editMode');
    }

    public function editMode(Request $request){
        $user_mode = auth()->user();
        $user_mode->update([
            'edit_mode' => $request['edit_mode']
        ]);
        return redirect('/profile');
    }

    public function showProfile(){
        $user = auth()->user();
        if ($user->edit_mode == 0 OR $user->edit_mode == NULL){
            $user->edit_mode = 'read';
        } else $user->edit_mode = 'edit';

        $ipLock = DB::table('ip_locks')->where('login', $user->name)->first();
        if ($ipLock == '0'){
            $user->ip_lock = 'disabled';
        } else $user->ip_lock = 'enabled';

        return view('showProfile', compact('user'));
    }

    public function editPassword(Request $request)
    {
        $authUserPasswordId = auth()->user()->passwords()->pluck('id');
        $request->validate([
            'old_password' => ['required'],
            'password' => ['required'],
            'password-confirm' => ['same:password'],
            'code' => ['required']
        ]);
        $passwordKey = Controller::generateSalt();
        $oldKey = auth()->user()->passwordkey;
        $password = $this->checkOldCode($request, $passwordKey);

        User::all()->find(auth()->user()->id)->update([
            'password' => $password,
            'code' => $request['code'],
            'passwordkey' => $passwordKey
        ]);

        $this->updateUserPasswords($oldKey, $passwordKey, $authUserPasswordId);
        session()->invalidate();

        session()->regenerateToken();
        return redirect("/login");
    }

    public function checkOldCode($request, $passwordKey){
        $oldCode = auth()->user()->code;

        $oldUserPassword = auth()->user()->password;
        $oldKey = auth()->user()->passwordkey;
        if ($oldCode == 'hmac'){
            $password = $this->checkOldHmacPassword($request, $passwordKey, $oldKey, $oldUserPassword);
        }
        elseif ($oldCode == 'sha512'){
            $password = $this->checkOldSha512Password($request, $passwordKey, $oldKey, $oldUserPassword);
        } else $password = null;
        return $password;
    }

    public function checkOldHmacPassword($request, $passwordKey, $oldKey, $oldUserPassword){
        $oldPassword = hash_hmac('sha512', $request['old_password'], $oldKey);
        if ($oldPassword == $oldUserPassword) {
            $password = $this->setNewPassword($request, $passwordKey);
            return $password;
        }
        else dd("podano zle haslo");
    }

    public function checkOldSha512Password($request, $passwordKey, $oldKey, $oldUserPassword ){
        $appkey = AppKey::appKey ;
        $oldPassword = hash("sha512", $request['old_password'].$oldKey.$appkey);
        if ($oldPassword == $oldUserPassword){
            $password = $this->setNewPassword($request, $passwordKey);
            return $password;
        } else dd("podano zle haslo");
    }

    public function setNewPassword($request, $passwordKey){
        if ($request['code'] == 'hmac') {
            $password = hash_hmac('sha512', $request->password, $passwordKey);
        }
        elseif ($request['code'] == 'sha512') {
            $password = hash('sha512', ($request->password).$passwordKey.AppKey::appKey);
        } else $password = null;
        return $password;
    }

    public function updateUserPasswords($oldPasswordKey, $passwordKey, $authUserPasswordId){

        foreach ($authUserPasswordId as $passwordId){
            $appKey = AppKey::appKey ;
            $ciphering = "AES-256-CBC";
            $password = DB::table('passwords')->where('id', $passwordId)->first();
            $userPassword = openssl_decrypt ($password->password, $ciphering, $oldPasswordKey, 0, $appKey);
            Password::find($passwordId)->update([
                'password' => openssl_encrypt( $userPassword , $ciphering, $passwordKey, 0, $appKey  )
            ]);
        }
        return true;
    }


}
