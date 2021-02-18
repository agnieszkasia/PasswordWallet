<?php

namespace Tests\Unit;

use App\UserFunction;
use Tests\TestCase;

/**
 * Class UserFunctionTest.
 *
 * @covers \App\UserFunction
 */
class UserFunctionTest extends TestCase
{
    /**
     * @var UserFunction
     */
    protected $userFunction;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->userFunction = new UserFunction();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->userFunction);
    }
}
