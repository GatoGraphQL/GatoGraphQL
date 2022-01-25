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
        self::$graphQLServer = new GraphQLServer(
            static::getGraphQLServerComponentClasses()
        );
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

    /**
     * @dataProvider graphQLServerExecutionProvider
     */
    public function testGraphQLServerExecution(string $query, array $expectedResponse): void
    {
        $response = self::getGraphQLServer()->execute($query);
        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedResponse),
            $response->getContent()
        );
    }

    abstract public function graphQLServerExecutionProvider(): array;
}
