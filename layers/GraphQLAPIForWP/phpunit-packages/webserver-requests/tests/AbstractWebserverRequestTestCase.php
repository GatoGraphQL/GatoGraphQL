<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PHPUnit\Framework\TestCase;

abstract class AbstractWebserverRequestTestCase extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::setUpWebserverRequestTests();
    }

    protected static function setUpWebserverRequestTests(): void
    {
    }

    public static function tearDownAfterClass(): void
    {
        static::tearDownWebserverRequestTests();
        parent::tearDownAfterClass();
    }

    protected static function tearDownWebserverRequestTests(): void
    {
    }
}
