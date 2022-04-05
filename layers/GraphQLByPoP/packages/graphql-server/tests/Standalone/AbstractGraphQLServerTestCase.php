<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer;
use PHPUnit\Framework\TestCase;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

abstract class AbstractGraphQLServerTestCase extends TestCase
{
    private static ?GraphQLServer $graphQLServer = null;

    public static function setUpBeforeClass(): void
    {
        self::$graphQLServer = new GraphQLServer(
            static::getGraphQLServerComponentClasses(),
            static::getGraphQLServerComponentClassConfiguration()
        );
    }

    protected function getService(string $service): mixed
    {
        return InstanceManagerFacade::getInstance()->getInstance($service);
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
     * @return array<string,mixed>
     */
    protected static function getGraphQLServerComponentClassConfiguration(): array
    {
        return [];
    }

    protected function assertGraphQLQueryExecution(string $query, array $expectedResponse, array $variables = [], ?string $operationName = null): void
    {
        $response = self::getGraphQLServer()->execute($query, $variables, $operationName);
        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedResponse),
            $response->getContent()
        );
    }
}
