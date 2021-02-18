<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\FunctionController;
use Tests\TestCase;

/**
 * Class FunctionControllerTest.
 *
 * @covers \App\Http\Controllers\FunctionController
 */
class FunctionControllerTest extends TestCase
{
    /**
     * @var FunctionController
     */
    protected $functionController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->functionController = new FunctionController();
        $this->app->instance(FunctionController::class, $this->functionController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->functionController);
    }
}
