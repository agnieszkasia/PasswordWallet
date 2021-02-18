<?php

namespace Tests\Unit;

use App\UserFunctionAction;
use Tests\TestCase;

/**
 * Class UserFunctionActionTest.
 *
 * @covers \App\UserFunctionAction
 */
class UserFunctionActionTest extends TestCase
{
    /**
     * @var UserFunctionAction
     */
    protected $userFunctionAction;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->userFunctionAction = new UserFunctionAction();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->userFunctionAction);
    }
}
