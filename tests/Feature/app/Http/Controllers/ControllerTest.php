<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Class ControllerTest.
 *
 * @covers \App\Http\Controllers\Controller
 */
class ControllerTest extends TestCase
{
    /**
     * @var Controller
     */
    protected $controller;

    use RefreshDatabase;

    public function testGenerateSalt(){
        $controller = new Controller();
        $result = $controller->generateSalt();
        $this->assertIsString($result);
    }

    public function test_password_is_encrypted(){
        $data = Request::create('login','POST',[
            'code' => 'hmac',
            'password' => '1234'
        ]);

        $controller = new Controller();
        $result = $controller->encryptPassword($data);

        $this->assertNotEquals($data['password'],$result );
    }
}
