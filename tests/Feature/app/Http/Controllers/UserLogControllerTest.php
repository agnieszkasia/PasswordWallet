<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\UserLogController;
use App\IpLock;
use App\LockedUser;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Class FailedLoginAttemptTest.
 *
 * @covers \App\FailedLoginAttempt
 */
class UserLogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_attempts_are_recorded(){
        $user = factory(User::class)->create();

        $this->post('/login', [
            'name' => 'test',
            'password' => '12345',
        ]);

        $this->assertDatabaseHas('failed_login_attempts', [
            'user_id' => $user->id,
            'name' => 'test'
        ]);
        $this->assertGuest();
    }

    public function test_show_user_logs(){
        factory(User::class)->create();

        $this->post('/login', [
            'name' => 'test',
            'password' => '12345',
        ]);
        $this->post('/login', [
            'name' => 'test',
            'password' => '1234',
        ]);

        $this->get('/showUserLogs')
            ->assertOk()
            ->assertSee('fail')
            ->assertSee('success');
    }

    public function test_show_lock_time(){
        factory(User::class)->create();
        $this->post('/login', [
            'name' => 'test',
            'password' => 'qwert',
        ]);

        $this->post('/login', [
            'name' => 'test',
            'password' => 'wdsa',
        ])->assertSee('Too many login attempts')
            ->assertSee('Attempts: 2')
            ->assertOk();
    }

    public function test_show_ip_lock_time(){
        factory(User::class)->create();

        $this->post('/login', [
            'name' => 'test',
            'password' => 'qwert',
        ]);

        $this->post('/login', [
            'name' => 'test2',
            'password' => 'qwqert',
        ])->assertSee('Too many login attempts')
            ->assertSee('Attempts: 2')
            ->assertOk();
        $this->assertDatabaseHas('locked_users', [
            'ip' => '127.0.0.1'
        ]);
        $this->assertGuest();
    }

    public function test_show_login_settings_form(){
        $this->get('/loginSettings')
            ->assertOk()
            ->assertSeeText('Check IP on login');

    }

    public function test_user_change_login_settings(){
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->post('/loginSettings', ['lock' => '1'])
            ->assertRedirect('/showUserLogs');
        $this->assertDatabaseHas('ip_locks', [
            'login' => 'test',
            'lock' => '1'
        ]);
    }

    public function test_if_user_is_locked(){
        factory(LockedUser::class)->create();

        $request = Request::create('/login','POST',[
            'name' => 'test'
        ]);

        $controller = new UserLogController();
        $result = $controller->userIsLocked($request);
        $this->assertTrue($result);
        $this->assertDatabaseHas('locked_users', [
            'ip' => null,
            'login' => 'test'
        ]);
    }

    public function test_if_ip_is_locked(){
        factory(LockedUser::class)->create([
            'ip' => '127.0.0.1',
            'login' => null,
        ]);

        $request = Request::create('/login','POST',[
            'name' => 'test'
        ]);

        $controller = new UserLogController();
        $result = $controller->ipIsLocked($request);
        $this->assertTrue($result);
        $this->assertDatabaseHas('locked_users', [
            'ip' => '127.0.0.1',
            'login' => null
        ]);
    }

    public function test_user_has_too_many_login_attempts(){
        factory(LockedUser::class)->create([
            'lock_time' => '2020-12-01 00:00:00',
            'attempts' => '4'
        ]);

        $request = Request::create('/login','POST',[
            'name' => 'test'
        ]);

        $controller = new UserLogController();
        $controller->userHasTooManyLoginAttempts($request);
        $this->assertDatabaseHas('locked_users', [
            'login' => 'test',
            'attempts' => '5',
            'lock_time' => '2020-12-01 23:59:59'
        ]);
    }

    public function test_ip_has_too_many_login_attempts(){
        factory(LockedUser::class)->create([
            'login' => null,
            'ip' => '127.0.0.1',
            'lock_time' => '2020-12-01 00:00:00',
            'attempts' => '4'
        ]);

        $request = Request::create('/login','POST',[
            'name' => 'test'
        ]);

        $controller = new UserLogController();
        $controller->ipHasTooManyLoginAttempts($request);
        $this->assertDatabaseHas('locked_users', [
            'ip' => '127.0.0.1',
            'attempts' => '5',
            'lock_time' => '2020-12-01 23:59:59'
        ]);
    }

    public function test_if_user_has_ip_lock(){
        factory(IpLock::class)->create();
        $request['name'] = 'test';

        $controller = new UserLogController();
        $result = $controller->checkIfUserHasIpLock($request);
        $this->assertTrue($result);
    }

    public function test_delete_user_lockout(){
        factory(IpLock::class)->create();

        factory(LockedUser::class)->create([
            'login' => null,
            'ip' => '127.0.0.1',
            'lock_time' => '2020-12-01 00:00:00',
            'attempts' => '2'
        ]);

        $request = Request::create('/login','POST',[
            'name' => 'test'
        ]);

        $controller = new UserLogController();
        $result = $controller->deleteUserLockout($request);
        $this->assertDatabaseMissing('locked_users', [
            'ip' => '127.0.0.1',
        ]);
        $this->assertCount(0, LockedUser::all());
        $this->assertTrue($result);
    }

}
