<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer;
use PHPUnit\Framework\TestCase;

abstract class AbstractGraphQLServerTestCase extends TestCase
{
    private static ?GraphQLServer $graphQLServer = null;

    public static function setUpBeforeClass(): void
    {
        if (self::$graphQLServer === null) {
            self::$graphQLServer = new GraphQLServer(
                static::getGraphQLServerComponentClasses()
            );
        }
    }

    protected static function getGraphQLServer(): GraphQLServer
    {
        return self::$graphQLServer;
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

    /**
     * @dataProvider graphQLServerExecutionProvider
     */
    public function testGraphQLServerExecution(string $query, array $response): void
    {
        $this->expectOutputString(json_encode($response));
        self::getGraphQLServer()->execute($query);
    }

    abstract public function graphQLServerExecutionProvider(): array;
}
