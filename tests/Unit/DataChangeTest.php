<?php

namespace Tests\Unit;

use App\DataChange;
use Tests\TestCase;

/**
 * Class DataChangeTest.
 *
 * @covers \App\DataChange
 */
class DataChangeTest extends TestCase
{
    /**
     * @var DataChange
     */
    protected $dataChange;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->dataChange = new DataChange();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->dataChange);
    }
}
