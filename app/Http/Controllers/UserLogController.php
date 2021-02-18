<?php
/**
 * Created by PhpStorm.
 * User: G
 * Date: 13.11.2020
 * Time: 13:13
 */

namespace App\Http\Controllers;


use App\FailedLoginAttempt;
use App\IpLock;
use App\LockedUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserLogController
{
    public function create(array $data)
    {
        FailedLoginAttempt::create([
            'name' => $data['name'],
            'ip_address' => $data['ip_address'],
            'user_id' => $data['user_id'],
            'status' => $data['status'],
            'time' => $data['time'],
            'attempts' => $data['attempts']
        ]);
    }

    public function createFail($request){
        $user = User::all()->where('name', $request['name'])->first();
        if (empty($user)){
            $user = User::all()->where('email', $request->input('name'))->first();
        }
        $loginAttempt = FailedLoginAttempt::all()->where('name', $user->name)
            ->where('status', 'fail')->first();
        if($loginAttempt != null){
            $loginAttempt->update([
                'time' => Carbon::now(),
                'attempts' => $loginAttempt['attempts']+1
            ]);
            return true;
        }
        else {
            $this->create([
                'name' => $request['name'],
                'ip_address' => $request->getClientIp(),
                'user_id' => $user->id,
                'status' => 'fail',
                'time' => Carbon::now(),
                'attempts' => 1
            ]);
            return false;
        }
    }

    public function createSuccess($request){
        $user = User::all()->where('name', $request->input('name'))->first();
        if (empty($user)){
            $user = User::all()->where('email', $request->input('name'))->first();
        }
        $loginAttempt = FailedLoginAttempt::all()->where('name', $user->name)
            ->where('status', 'success')->first();
        if($loginAttempt != null){
            $loginAttempt->update([
                'time' => Carbon::now()
            ]);
            return true;
        }
        else {
            $this->create([
                'name' => $request['name'],
                'ip_address' => $request->getClientIp(),
                'user_id' => $user->id,
                'status' => 'success',
                'time' => Carbon::now(),
                'attempts' => null
            ]);
            return false;
        }
    }

    public function showUserLogs(){

        $user_id = auth()->user()->id;
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
        $lock = $lockData['lock'];

        return view('showLogs', compact('failLogData', 'successLogData', 'lock'));
    }

    public function showLockTime(Request $request){
        $name = $request['name'];
        $userFailLog = LockedUser::all()->where('login', $request['name'])->first();

        $lockTime = Carbon::parse($userFailLog['lock_time'])->diffForHumans(Carbon::now());
        $attempts = $userFailLog['attempts'];

        $ip = $request->getClientIp();
        return view('showLockTime', compact('lockTime', 'name', 'ip', 'attempts'));
    }

    public function showIpLockTime(Request $request){
        $name = $request['name'];
        $userFailLog = LockedUser::all()->where('ip', $request->getClientIp())->first();

        $lockTime = Carbon::parse($userFailLog['lock_time'])->diffForHumans(Carbon::now());
        $attempts = $userFailLog['attempts'];

        $ip = $request->getClientIp();
        return view('showLockTime', compact('lockTime', 'name', 'ip', 'attempts'));
    }

    public function showLoginSettingsForm(){
        return view('loginSettings');
    }

    public function loginSettings(Request $request){
        $user = User::all()->where('id',Auth::user()->getAuthIdentifier())->first();
        $ip_lock = IpLock::all()->where('login', $user['name'])->first();
        if ($ip_lock != null){
            $ip_lock->update([
                'lock' => $request['lock']
            ]);
        }
        else {
            IpLock::create([
                'lock' => $request['lock'],
                'login' => $user['name']
            ]);
        }
        return redirect('/showUserLogs');
    }

    public function userIsLocked($request){
        $userFailLog = LockedUser::all()->where('login', $request['name'])->first();
        if ($userFailLog = 'null') {
//            dd($userFailLog);
            return false;
        }
        else {
            $myDate = date("Y-m-d H:i:s", strtotime($userFailLog['lock_time']));
            $curDateTime = date("Y-m-d H:i:s");
            if ($myDate > $curDateTime){
                return true;
            }
            else return false;
        }

    }

    public function ipIsLocked($request){
        $userFailLog = LockedUser::all()->where('ip', $request->getClientIp())->first();
        if ($userFailLog = 'null'){
            return true;
        }
        else {
            $myDate = date("Y-m-d H:i:s", strtotime($userFailLog['lock_time']));
            $curDateTime = date("Y-m-d H:i:s");
            if ($myDate > $curDateTime){
                return true;
            }
            else return false;
        }

    }

    public function userHasTooManyLoginAttempts(Request $request){
        $controller = new UserLogController();
        $userFailLog = LockedUser::all()->where('login', $request['name'])->first();
        if ($userFailLog = 'null'){
            return false;
        }
        else {
            switch ($userFailLog['attempts']) {
                case null:
                    LockedUser::create([
                        'ip' => null,
//                       'ip' => $request->getClientIp(),
                        'login' => $request['name'],
                        'attempts' => '1',
                    ]);
                    return false;
                case '1':
                    $time = Carbon::now()->addSeconds(5);
                    $userFailLog->update([
                        'attempts' => '2',
                        'lock_time' => $time
                    ]);
                    return $controller->showLockTime($request);
                case 2:
                    $time = Carbon::now()->addSeconds(10);
                    $userFailLog->update([
                        'attempts' => '3',
                        'lock_time' => $time
                    ]);
                    return $controller->showLockTime($request);
                case 3:
                    $time = Carbon::now()->addMinutes(2);
                    $userFailLog->update([
                        'attempts' => '4',
                        'lock_time' => $time
                    ]);
                    return $controller->showLockTime($request);
                case 4:
                    $time = Carbon::now()->endOfDay();
                    $userFailLog->update([
                        'attempts' => '5',
                        'lock_time' => $time
                    ]);
                    return $controller->showLockTime($request);
            }
        }
    }

    public function ipHasTooManyLoginAttempts(Request $request){
        $controller = new UserLogController();
        $userFailLog = LockedUser::all()->where('ip', $request->getClientIp())->first();


        if ($userFailLog = 'null'){
            return false;
        }
        else {
            switch ($userFailLog['attempts']) {
                case null:
                    LockedUser::create([
//                    'ip' => null,
                        'ip' => $request->getClientIp(),
                        'login' => null,
                        'attempts' => '1',
                    ]);
                    return false;
                case '1':
                    $time = Carbon::now()->addSeconds(5);
                    $userFailLog->update([
                        'attempts' => '2',
                        'lock_time' => $time
                    ]);
                    return $controller->showLockTime($request);
                case 2:
                    $time = Carbon::now()->addSeconds(10);
                    $userFailLog->update([
                        'attempts' => '3',
                        'lock_time' => $time
                    ]);
                    return $controller->showLockTime($request);
                case 3:
                    $time = Carbon::now()->addMinutes(2);
                    $userFailLog->update([
                        'attempts' => '4',
                        'lock_time' => $time
                    ]);
                    return $controller->showLockTime($request);
                case 4:
                    $time = Carbon::now()->endOfDay();
                    $userFailLog->update([
                        'attempts' => '5',
                        'lock_time' => $time
                    ]);
                    return $controller->showLockTime($request);
            }
        }
    }

    public function checkIfUserHasIpLock($request){
        $ip_lock = IpLock::all()->where('login', $request['name'])->first();
        if ($ip_lock = 'null'){
            return false;
        }
        else if ($ip_lock['lock'] != 0){
            return true;
        }
        elseif($ip_lock['lock'] == 0) return false;
    }

    public function deleteUserLockout($request){
        $login = $request['name'];
        DB::table('locked_users')->where('login', $login)->delete();
        if ($this->checkIfUserHasIpLock($request) == true){
            DB::table('locked_users')->where('ip', $request->getClientIp())->delete();
            return true;
        }
        return false;
    }
//
//    public function checkLastAttempt(){
//        $user_id = auth()->user()->id;
//        $lastLog = DB::select("SELECT *
//                                      FROM failed_login_attempts
//                                      WHERE user_id = '$user_id'
//                                      AND time = (
//                                          SELECT MAX(time)
//                                          FROM failed_login_attempts
//                                          WHERE user_id = '$user_id'
//                                          GROUP BY user_id )
//                                      ");
//        if ($lastLog['status'] == 'success')
//            return true;
//        else return false;
//    }

}
