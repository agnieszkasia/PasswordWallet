<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\HomeController;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class HomeControllerTest.
 *
 * @covers \App\Http\Controllers\HomeController
 */
class HomeControllerTest extends TestCase
{
    /**
     * @var HomeController
     */
    protected $homeController;

    use RefreshDatabase;

    public function test_authenticated_user_can_see_home_page(){
        $this->actingAs(factory(User::class)->make(['name' => 'test']));
        $this->get('/home')->assertOk()->assertSeeText('Your passwords');
    }
}
