<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use GraphQLByPoP\GraphQLServer\Server\StandaloneGraphQLServer;
use PHPUnit\Framework\TestCase;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\Facades\Variables\VariableManagerFacade;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Module\ModuleInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use stdClass;

abstract class AbstractGraphQLServerTestCase extends TestCase
{
    private static StandaloneGraphQLServer $standaloneGraphQLServer;

    public static function setUpBeforeClass(): void
    {
        self::$standaloneGraphQLServer = static::createGraphQLServer();
    }

    protected static function createGraphQLServer(): StandaloneGraphQLServer
    {
        return new StandaloneGraphQLServer(
            static::getGraphQLServerModuleClasses(),
            static::getGraphQLServerModuleClassConfiguration(),
            static::getGraphQLServerSystemContainerCompilerPassClasses(),
            static::getGraphQLServerApplicationContainerCompilerPassClasses()
        );
    }

    protected function getService(string $service): mixed
    {
        return InstanceManagerFacade::getInstance()->getInstance($service);
    }

    protected static function getGraphQLServer(): StandaloneGraphQLServer
    {
        return self::$standaloneGraphQLServer;
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    protected static function getGraphQLServerModuleClasses(): array
    {
        return [];
    }

    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>>
     */
    protected static function getGraphQLServerModuleClassConfiguration(): array
    {
        return [];
    }

    /**
     * @return array<class-string<CompilerPassInterface>>
     */
    protected static function getGraphQLServerSystemContainerCompilerPassClasses(): array
    {
        return [];
    }

    /**
     * @return array<class-string<CompilerPassInterface>>
     */
    protected static function getGraphQLServerApplicationContainerCompilerPassClasses(): array
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
        $expectedResponseJSON = json_encode($expectedResponse, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($expectedResponseJSON === false) {
            throw new RuntimeException('Encoding the expected response as JSON failed');
        }
        $responseContent = $response->getContent();
        if ($responseContent === false) {
            throw new RuntimeException('Obtaining the content of the response failed');
        }
        $this->assertJsonStringEqualsJsonString(
            $expectedResponseJSON,
            $responseContent
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
            $graphQLVariables = json_decode($graphQLVariablesJSON);
            if (!is_array($graphQLVariables) && !($graphQLVariables instanceof stdClass)) {
                throw new RuntimeException(
                    sprintf(
                        'Decoding the JSON inside file "%s" failed',
                        $variablesFile
                    )
                );
            }
            $graphQLVariables = (array) $graphQLVariables;
            $variableManager = VariableManagerFacade::getInstance();
            $graphQLVariables = $variableManager->recursivelyConvertVariableEntriesFromArrayToObject($graphQLVariables);
        }

        $response = self::getGraphQLServer()->execute($graphQLQuery, $graphQLVariables, $operationName);
        $responseContent = $response->getContent();
        if ($responseContent === false) {
            throw new RuntimeException('Obtaining the content of the response failed');
        }

        $responseContent = $this->adaptResponseContent($responseContent);

        // Allow to override method
        $this->doAssertFixtureGraphQLQueryExecution($expectedResponseFile, $responseContent);
    }

    protected function adaptResponseContent(string $responseContent): string
    {
        return $responseContent;
    }

    protected function doAssertFixtureGraphQLQueryExecution(string $expectedResponseFile, string $actualResponseContent): void
    {
        $this->assertJsonStringEqualsJsonFile(
            $expectedResponseFile,
            $actualResponseContent
        );
    }

    protected function getDataName(): string
    {
        return (string) $this->dataName();
    }
}
