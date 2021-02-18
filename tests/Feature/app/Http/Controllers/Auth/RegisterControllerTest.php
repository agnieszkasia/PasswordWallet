<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Class RegisterControllerTest.
 *
 * @covers \App\Http\Controllers\Auth\RegisterController
 */
class RegisterControllerTest extends TestCase
{
    /**
     * @var RegisterController
     */
    protected $registerController;

    public function data(){
        return [
            'name' => 'test',
            'password' => '1a5397875cb4c866dcab9a3b423775ef4f15bc11bfe50187a3064f4e633d106b452e03d4d260bf62f15a67df08ae6ab893f0030c18e86c32550b139b582d465b',
            'passwordkey' => 'owrU":pe!EorCNuD',
            'code' => 'hmac',
        ];
    }

    use RefreshDatabase;

    public function test_user_can_see_register_page(){
        $this->get('/register')->assertSeeText('Register');
    }

    public function test_user_can_be_added_through_the_form(){
        $data = Request::create('/register','POST',[
                'name' => 'test',
                'password' => '1234',
                'password-confirm' => '1234',
                'code' => 'hmac'
            ]);

        $controller = \Mockery::mock(Controller::class);
        $controller->shouldReceive('encryptPassword')
            ->andReturns($response = [
                'password' => '1a5397875cb4c866dcab9a3b423775ef4f15bc11bfe50187a3064f4e633d106b452e03d4d260bf62f15a67df08ae6ab893f0030c18e86c32550b139b582d465b',
                'passwordkey' => 'owrU":pe!EorCNuD',
                ]);
        $this->app->instance(Controller::class, $controller );

        $registerController = new RegisterController();
        $registerController->register($data);

        $this->assertCount(1, User::all());
    }

    public function test_user_can_be_created(){
        $data = $this->data();

        $controller = new RegisterController();
        $controller->create($data);

        $this->assertCount(1, User::all());
    }
}
