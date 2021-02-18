<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Http\Request;
/**
 * Class LoginControllerTest.
 *
 * @covers \App\Http\Controllers\Auth\LoginController
 */
class LoginControllerTest extends TestCase
{
    /**
     * @var LoginController
     */
    protected $loginController;

    use RefreshDatabase;

    public function test_user_can_see_login_page(){
        $this->get('/login')->assertSeeText('Login');
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = factory(User::class)->create();

        $this->post('/login', [
            'name' => $user->name,
            'password' => '1234',
        ])->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_password(){
        factory(User::class)->create();

        $this->from('/login')->post('/login', [
            'name' => 'test',
            'password' => '12345',
        ])->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_check_if_password_is_correct(){
        $user = factory(User::class)->make();
        $this->actingAs($user);

        $password = '1234';
        $controller = new LoginController;
        $result = $controller->checkPassword($user, $password);
        $this->assertTrue($result);
    }

    public function test_check_if_password_is_hmac(){
        $user = factory(User::class)->make();
        $this->actingAs($user);

        $controller = new LoginController;
        $result = $controller->checkIfHmac($user);
        $this->assertTrue($result);
    }

    public function test_check_hmac_password(){
        $password = '1234';
        $user = factory(User::class)->make();
        $this->actingAs($user);

        $controller = new LoginController;
        $result = $controller->checkHmacPassword($password, $user);
        $this->assertTrue($result);
    }

    public function test_check_sha512_password(){
        $password = '1234';
        $user = factory(User::class)->make([
            'password' => 'aab6940b8b13c878c5a9e2118ac0caf64a9a990a5f6133b60c06fd51ec130f52aa63075c3c51a54cb59cc5317773becb76069d1d7d95827a1959d949b5c3fc13',
            'passwordkey' => '$Xu_G_P=hEmO>Cob',
            'code' => 'sha512',
        ]);

        $this->actingAs($user);
        $controller = new LoginController;
        $result = $controller->checkSha512Password($password, $user);
        $this->assertTrue($result);
    }
}
