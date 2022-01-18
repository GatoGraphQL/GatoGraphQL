<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer;

use GraphQLByPoP\GraphQLServer\StandaloneGraphQLApp;
use PHPUnit\Framework\TestCase;

abstract class AbstractStandaloneGraphQLAppTestCase extends TestCase
{
    protected static ?StandaloneGraphQLApp $standaloneGraphQLApp = null;

    public static function setUpBeforeClass(): void
    {
        if (self::$standaloneGraphQLApp === null) {
            self::$standaloneGraphQLApp = new StandaloneGraphQLApp(static::getGraphQLServerComponentClasses());
        }
    }

    /**
     * @return string[]
     */
    protected static function getGraphQLServerComponentClasses(): array
    {
        return [];
    }

    public static function tearDownAfterClass(): void
    {
        self::$standaloneGraphQLApp = null;
    }
}
