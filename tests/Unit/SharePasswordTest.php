<?php

namespace Tests\Unit;

use App\SharePassword;
use Tests\TestCase;

/**
 * Class SharePasswordTest.
 *
 * @covers \App\SharePassword
 */
class SharePasswordTest extends TestCase
{
    /**
     * @var SharePassword
     */
    protected $sharePassword;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->sharePassword = new SharePassword();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->sharePassword);
    }
}
