<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer;
use PHPUnit\Framework\TestCase;

abstract class AbstractGraphQLServerTestCase extends TestCase
{
    protected static ?GraphQLServer $graphQLServer = null;

    public static function setUpBeforeClass(): void
    {
        if (self::$graphQLServer === null) {
            self::$graphQLServer = new GraphQLServer(static::getGraphQLServerComponentClasses());
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
        self::$graphQLServer = null;
    }
}
