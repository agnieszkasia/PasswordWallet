<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\User;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\Feature\Http\Controllers\PasswordControllerTest;

/**
 * Class UserControllerTest.
 *
 * @covers \App\Http\Controllers\UserController
 */
class UserControllerTest extends TestCase
{
    /**
     * @var UserController
     */
    protected $userController;

    use RefreshDatabase;

    public function data(){
        return [
            'name' => 'test',
            'password' => '1a5397875cb4c866dcab9a3b423775ef4f15bc11bfe50187a3064f4e633d106b452e03d4d260bf62f15a67df08ae6ab893f0030c18e86c32550b139b582d465b',
            'passwordkey' => 'owrU":pe!EorCNuD',
            'code' => 'hmac',
        ];
    }

    public function request(){
        return [
            'code' => 'hmac',
            'password' => '12345',
            'old_password' => '1234'
        ];
    }

    public function test_only_logged_user_can_see_edit_password_page(){
        $this->actingAs(factory(User::class)->make());
        $this->get('/editPassword')->assertOk()->assertSeeText('Edit password');
    }

    public function test_password_is_updated(){
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $password = $user['password'];

        $request = Request::create('editPassword','POST',$this->request());

        $controller = new UserController();
        $controller->editPassword($request);
        $user = User::all()->first();

        $this->assertNotEquals($password, $user['password']);
    }

    public function test_new_password_is_set(){
        $request = Request::create('editPassword','POST',$this->request());
        $controller = new UserController();
        $user = $this->data();
        $result = $controller->setNewPassword($request, $user['passwordkey']);
        $this->assertNotEquals($user['password'], $result);
    }
}
