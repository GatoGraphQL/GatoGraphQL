<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer;
use PHPUnit\Framework\TestCase;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
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
            static::getGraphQLServerModuleClasses(),
            static::getGraphQLServerModuleClassConfiguration()
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
    protected static function getGraphQLServerModuleClasses(): array
    {
        return [];
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getGraphQLServerModuleClassConfiguration(): array
    {
        return [];
    }

    /**
     * @param mixed[] $expectedResponse
     * @param array<string,mixed> $variables
     */
    protected function assertGraphQLQueryExecution(
        string|ExecutableDocument $queryOrExecutableDocument,
        array $expectedResponse,
        array $variables = [],
        ?string $operationName = null
    ): void {
        $response = self::getGraphQLServer()->execute($queryOrExecutableDocument, $variables, $operationName);
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

        $response = self::getGraphQLServer()->execute($graphQLQuery, $graphQLVariables, $operationName);

        // Allow to override method
        $this->doAssertFixtureGraphQLQueryExecution($expectedResponseFile, $response->getContent());
    }

    protected function doAssertFixtureGraphQLQueryExecution(string $expectedResponseFile, string $actualResponseContent): void
    {
        $this->assertJsonStringEqualsJsonFile(
            $expectedResponseFile,
            $actualResponseContent
        );
    }
}
