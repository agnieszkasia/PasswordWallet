<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PasswordController;
use App\Password;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Request;
use Tests\Feature\Http\Controller;

/**
 * Class PasswordControllerTest.
 *
 * @covers \App\Http\Controllers\PasswordController
 */
class PasswordControllerTest extends TestCase
{
    /**
     * @var PasswordController
     */
    protected $passwordController;

    use RefreshDatabase;

    public function test_only_logged_user_can_see_add_password_page(){
        $this->actingAs(factory(User::class)->make());
        $this->get('/addPassword')->assertOk()->assertSeeText('Add password');
    }

    public function test_password_can_be_added_through_the_form(){
        $this->actingAs(factory(User::class)->make());

        $data = [
            'user_id' => '1',
            'web_address' => 'testing.com',
            'login' => 'test',
            'password' => '123',
            'description' => 'opis',
        ];
        $this->post(route('addPassword'), $data);
        $this->assertCount(1, Password::all());
    }

    public function test_show_users_password_information(){
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $password = factory(Password::class)->create();
        Request::create('showPassword','POST',[
            'passwordkey' => $user['passwordkey'],
            'password_id' => '2'
        ]);
        $response = $this->post('/showPassword/'.$password['id']);
        $response->assertSeeText('Address');
    }
}
