<?php

namespace App\Http\Controllers;
use App\AppKey;

use App\DataChange;
use App\SharePassword;
use App\UserFunctionAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Password;
use PhpParser\Node\Scalar\String_;

class PasswordController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function showAddPasswordForm()
    {
        return view('addPassword');
    }

    public function showAllPasswords()
    {
        $user_id = auth()->user()->id;
        $password = DB::select("SELECT * FROM passwords WHERE user_id = '$user_id' AND deleted = '0'");

        $email = Auth::user()->email;
        $sharedPassword = DB::select("SELECT * FROM passwords p INNER JOIN share_passwords sp ON sp.password_id = p.id WHERE email = '$email'");

        $userActivity = DB::select("SELECT user_id, function_id, function_name, created_at, a.id
                                      FROM data_changes a
                                      INNER JOIN user_functions f
                                      ON f.id = a.function_id
                                      WHERE user_id = '$user_id'
                                      ORDER BY created_at
                                      ");

        return view('showAllPasswords', compact('password', 'sharedPassword', 'userActivity'));
    }

    public function addPassword (Request $request){

        $request['id'] = '0';
        $this->createDataChange($request['user_id'],1, $request);
        $passwordKey = $request['passwordkey'];

        $password = $request['password'];
        $request['password'] = $this->encryptPagePassword($password, $passwordKey);

        $data = request()->validate([
            'user_id' => ['string'],
            'web_address' => ['string', 'max:191'],
            'login' => ['string', 'max:191'],
            'password' => ['string'],
            'description' => ['string', 'max:191'],
        ]);
        $password = $this->create($request->all());
        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect("/home");
    }

    public function encryptPagePassword($userPassword, $passwordKey){
        $appKey = AppKey::appKey ;
        $ciphering = "AES-256-CBC";
        $password = openssl_encrypt( $userPassword , $ciphering, $passwordKey, 0, $appKey  );
        return $password;
    }


    public function showPassword(Request $request){
        $user_id = auth()->user()->id;
        $password_id = $request->input('password_id');
        $passwordData = DB::table('passwords')->where('id', $password_id)->first();

        if ($passwordData->user_id == $user_id){
            $passwordKey = $request->input('passwordkey');
            $password = $passwordData->password = $this->decryptPassword($passwordData, $passwordKey);
        }
        else{
            $password = DB::table('share_passwords')->where('password_id', $request->input('password_id'))->first();
            $passwordKey = $password->passwordKey;
            $passwordData->password = $this->decryptPassword($password, $passwordKey);
        }

        $userActivity = DB::select("SELECT user_id, function_id, function_name, created_at, a.id
                                      FROM data_changes a
                                      INNER JOIN user_functions f
                                      ON f.id = a.function_id
                                      WHERE user_id = '$user_id'
                                      AND password_id = '$password_id'
                                      ORDER BY created_at
                                      ");

        return view('showPassword', compact('passwordData', 'password', 'userActivity'));
    }


    public function decryptPassword($passwordData, $passwordKey){
        $ciphering = "AES-256-CBC";
        $appkey = AppKey::appKey ;
        $password = openssl_decrypt($passwordData->password, $ciphering, $passwordKey, 0, $appkey);
        return $password;
    }

    public function create(array $data)
    {
        return Password::create([
            'deleted' => '0',
            'user_id' => $data['user_id'],
            'web_address' => $data['web_address'],
            'login' => $data['login'],
            'password' => $data['password'],
            'description' => $data['description'],
        ]);
    }

    public function createUserFunctionAction($user_id, $function_id)
    {
        return DataChange::create([
            'user_id' => $user_id,
            'function_id' => $function_id
        ]);
    }

    public function createDataChange($user_id,$function_id, $data ){
        return DataChange::create([
            'user_id' => $user_id,
            'password_id' => $data['id'],
            'function_id' => $function_id,
            'previous_web_address' => $data['web_address'],
            'previous_description' => $data['description'],
            'previous_login' => $data['login'],
            'previous_password' => $data['password'],
        ]);
    }

    public function showSharePasswordForm(Request $request){
        $password_id = $request->input('password_id');

        $passwordKey = $request->input('passwordkey');
        $passwordData = DB::table('passwords')->where('id', $request->input('password_id'))->first();

        $password = $passwordData->password = $this->decryptPassword($passwordData, $passwordKey);

        return view('sharePassword', compact('password_id', 'password'));
    }

    public function sharePassword(Request $request){

        $passwordKey = Controller::generateSalt();

        $password = $request['password'];
        $request['password'] = $this->encryptPagePassword($password, $passwordKey);
        $passwordData = DB::table('share_passwords')->where('password_id', $request['password_id'])
                            ->where('email', $request['email'])->first();
        $pass = DB::table('users')->where('email', $request['email'])->first();
//        dd($pass);
        if (empty($pass) OR $pass->email == auth()->user()->email){
            return view('userError');
        }
        if ($request['email'] != auth()->user()->name AND $passwordData == null ) {
            SharePassword::create([
                'password_id' => $request['password_id'],
                'password' => $request['password'],
                'passwordKey' => $passwordKey,
                'email' => $request['email'],
            ]);
        }
        return redirect('showAllPasswords');
    }

    public function showPasswordSharing(Request $request){
        $password_id = $request->input('password_id');
        $users = DB::select("SELECT * FROM users u INNER JOIN share_passwords sp ON sp.email=u.email WHERE password_id = '$password_id'");

        return view('showPasswordSharing', compact('password_id', 'users'));
    }

    public function showEditPasswordForm (Request $request){
        if (auth()->user()->edit_mode == 1){
            $passwordData = DB::table('passwords')->where('id', $request->input('password_id'))->first();
            $passwordKey = $request->input('passwordkey');
            $password = $passwordData->password = $this->decryptPassword($passwordData, $passwordKey);
            return view('editPassword', compact('passwordData', 'password'));
        } else return view('accessError');
    }

    public function showDeletePasswordForm (Request $request){
        if (auth()->user()->edit_mode == 1){
            $password_id = $request->input('password_id');

            return view('deletePassword', compact('password_id'));
        } else return view('accessError');
    }

    public function showOwnerAccessError(){
        return view('ownerAccessError');
    }

    public function deletePassword(Request $request){

        $user_id = auth()->user()->id;
//        $this->createUserFunctionAction($user_id,3);
        $data = Password::all()->find($request->input('password_id'));
        $data['id'] = $request->input('password_id');
;        $this->createDataChange($user_id, 3, $data);

        Password::all()->find($request->input('password_id'))->update([
            'deleted' => '1'
        ]);

//        DB::table('passwords')->where('id', $request->input('password_id'))->delete();
        return redirect('showAllPasswords');
    }

    public function editPassword(Request $request){

        $request['id'] = $request['password_id'];
        $old_pass = Password::all()->find($request->input('password_id'));

        $this->createDataChange($request['user_id'],2, $old_pass);

        $passwordKey = $request['passwordkey'];
        $password = $request['password'];
        $pass = $this->encryptPagePassword($password, $passwordKey);

        Password::all()->find($request->input('password_id'))->update([
            'web_address' => $request['web_address'],
            'login' => $request['login'],
            'password' => $pass,
            'description' => $request['description'],
        ]);

        $passwordKey = Controller::generateSalt();

        $password = $request['password'];
        $request['password'] = $this->encryptPagePassword($password, $passwordKey);

        $sharedPass = SharePassword::all()->where('password_id', $request->input('password_id'))->first();
        if($sharedPass != null){
            $sharedPass->update([
                'password' => $request['password'],
                'passwordKey' => $passwordKey,
            ]);
        }
        return redirect('showAllPasswords');
    }

    public function restorePassword(Request $request){

        $user_id = auth()->user()->id;
        $data_change = DataChange::all()->find($request->input('data_id'));

        $pass_id = $data_change['password_id'];
        $password = Password::all()->find($pass_id);
//        dd($data_change);
        if ($password['deleted'] == '1'){

            $data['id'] = $data_change['password_id'];
            $data['web_address'] = $data_change['previous_web_address'];
            $data['description'] = $data_change['previous_description'];
            $data['login'] = $data_change['previous_login'];
            $data['password'] = $data_change['previous_password'];

            $this->createDataChange($user_id,4, $data);

            if ($data_change['function_id'] == '3'){
                $password->update([
                    'deleted' => '0'
                ]);
            }
            return redirect('showAllPasswords');
        } else {
            $data['id'] = $data_change['password_id'];
            $data['web_address'] = $data_change['previous_web_address'];
            $data['description'] = $data_change['previous_description'];
            $data['login'] = $data_change['previous_login'];
            $data['password'] = $data_change['previous_password'];

            $this->createDataChange($user_id,4, $data);

            $password->update([
                'web_address' => $data['web_address'],
                'description' => $data['description'],
                'login' => $data['login'],
                'password' => $data['password'],
            ]);
            return redirect('/showAllPasswords');
        }






    }
}
