<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', ['middleware' =>'guest', function(){
    return view('auth.login');
}]);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/functionCreate', 'FunctionController@showFunction')->name('functionCreate');
Route::get('/functionUpdate', 'FunctionController@showFunction')->name('functionUpdate');
Route::get('/functionDelete', 'FunctionController@showFunction')->name('functionDelete');


Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('loginUser');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register');

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/restore', 'PasswordController@restorePassword')->name('restore');

Route::get('/showAllPasswords', 'PasswordController@showAllPasswords')->name('showAllPasswords');
Route::get('/profile', 'UserController@showProfile')->name('showProfile');

Route::get('/addPassword', 'PasswordController@showAddPasswordForm')->name('addPassword');
Route::post('/addPassword', 'PasswordController@addPassword')->name('addPassword');

Route::get('/editPassword', 'UserController@showEditPasswordForm')->name('editMainPassword');
Route::post('/editPassword', 'UserController@EditPassword')->name('editMainPassword');

Route::get('/showUserLogs', 'UserLogController@showUserLogs')->name('showUserLogs');
Route::post('/showUserLogs', 'UserLogController@showUserLogs')->name('showUserLogs');

Route::get('/loginSettings', 'UserLogController@showLoginSettingsForm')->name('showLoginSettingsForm');
Route::post('/loginSettings', 'UserLogController@loginSettings')->name('loginSettings');

// Password Reset Routes...
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/profile/mode', 'UserController@showEditModeForm')->name('showEditModeForm');
Route::post('/profile/mode', 'UserController@editMode')->name('editMode');

Route::get('/showLockTime', 'UserLogController@showLockTime')->name('showLockTime');

Route::post('/password/delete', 'PasswordController@deletePassword')->name('deletePassword');
Route::post('/password/edit', 'PasswordController@editPassword')->name('editPassword');
Route::post('/password/share', 'PasswordController@sharePassword')->name('sharePassword');


//Route::get('/sharePassword/{id}', 'PasswordController@showSharePasswordForm')->name('showSharePasswordForm');
Route::post('/password/{id}/share', 'PasswordController@showSharePasswordForm')->name('showSharePasswordForm');

Route::post('/password/{id}/sharedFor', 'PasswordController@showPasswordSharing')->name('showPasswordSharing');

Route::post('/password/{id}/edit', 'PasswordController@showEditPasswordForm')->name('showEditPasswordForm');
Route::get('/password/{id}/edit', 'PasswordController@showOwnerAccessError')->name('showOwnerAccessError');

Route::post('/password/{id}/delete', 'PasswordController@showDeletePasswordForm')->name('showDeletePasswordForm');
Route::get('/password/{id}/delete', 'PasswordController@showOwnerAccessError')->name('showOwnerAccessError');

Route::get('/password/{id}', 'PasswordController@showPassword')->name('showPassword');
Route::post('/password/{id}', 'PasswordController@showPassword')->name('showPassword');

