<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer;
use PHPUnit\Framework\TestCase;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

abstract class AbstractGraphQLServerTestCase extends TestCase
{
    private static ?GraphQLServer $graphQLServer = null;

    public static function setUpBeforeClass(): void
    {
        self::$graphQLServer = static::createGraphQLServer();
    }

    protected static function createGraphQLServer(): GraphQLServer
    {
        return new GraphQLServer(
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

    /**
     * Test by passing "fixture" files, from which to extract the content.
     */
    protected function assertFixtureGraphQLQueryExecution(string $queryFile, string $expectedResponseFile, ?string $variablesFile = null, ?string $operationName = null): void
    {
        $graphQLQuery = file_get_contents($queryFile);
        if ($graphQLQuery === false) {
            $this->markTestIncomplete(
                sprintf(
                    'File "%s" (with the expected GraphQL query) does not exist.',
                    $queryFile
                )
            );
        }
        $graphQLVariables = [];
        if ($variablesFile !== null) {
            $graphQLVariablesJSON = file_get_contents($variablesFile);
            if ($graphQLVariablesJSON === false) {
                $this->markTestIncomplete(
                    sprintf(
                        'File "%s" (with the optional GraphQL variables) does not exist.',
                        $variablesFile
                    )
                );
            }
            $graphQLVariables = json_decode($graphQLVariablesJSON, true);
        }

        /**
         * Allow to inject extra functionality, such as
         * setting Guzzle response mocks for the particular
         * named dataset
         */
        $this->beforeFixtureGraphQLQueryExecution(
            $this->dataName(),
            $queryFile,
            $expectedResponseFile,
            $variablesFile,
            $operationName,
        );

        $response = self::getGraphQLServer()->execute($graphQLQuery, $graphQLVariables, $operationName);
        $this->assertJsonStringEqualsJsonFile(
            $expectedResponseFile,
            $response->getContent()
        );

        /**
         * Perform additional assertions, such as macking sure
         * that there are no mock Responses left in the queue.
         */
        $this->afterFixtureGraphQLQueryExecution(
            $this->dataName(),
        );
    }

    /**
     * Allow to inject extra functionality or override GraphQL variables.
     */
    protected function beforeFixtureGraphQLQueryExecution(string $dataName, string $queryFile, string $expectedResponseFile, ?string $variablesFile = null, ?string $operationName = null): void
    {
    }

    /**
     * Allow to perform additional assertions
     */
    protected function afterFixtureGraphQLQueryExecution(string $dataName): void
    {
    }
}
