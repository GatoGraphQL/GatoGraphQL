<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLByPoP\GraphQLServer\Standalone\FixtureTestCaseTrait;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractEndpointWebserverRequestTestCase;
use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

use function file_get_contents;
use function file_exists;
use function json_decode;

abstract class AbstractQueryExecutionFixtureWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
{
    use FixtureTestCaseTrait;
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * Retrieve all GraphQL query files and their expected
     * responses from under the "/fixture" folder
     */
    final protected function provideEndpointEntries(): array
    {
        $endpoint = $this->getEndpoint();

        $fixtureFolder = $this->getFixtureFolder();
        $graphQLQueryFileNameFileInfos = $this->findFilesInDirectory(
            $fixtureFolder,
            ['*.gql'],
            ['*.disabled.gql']
        );

        $providerItems = [];
        foreach ($graphQLQueryFileNameFileInfos as $graphQLQueryFileInfo) {
            $query = $graphQLQueryFileInfo->getContents();

            /**
             * From the GraphQL query file name, generate the remaining file names
             */
            $fileName = $graphQLQueryFileInfo->getFilenameWithoutExtension();
            $filePath = $graphQLQueryFileInfo->getPath();
            $graphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.json';
            if (!file_exists($graphQLResponseFile)) {
                $this->throwFileNotExistsException($graphQLResponseFile);
            }

            $variables = [];
            $graphQLVariablesFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.var.json';
            if (file_exists($graphQLVariablesFile)) {
                $variables = json_decode(
                    file_get_contents($graphQLVariablesFile),
                    true
                );
            }

            $dataName = $fileName;
            $providerItems[$dataName] = [
                'application/json',
                file_get_contents($graphQLResponseFile),
                $endpoint,
                [],
                $query,
                $variables,
            ];

            /**
             * Retrieve additional GraphQL responses to execute some "operationName"
             */
            $graphQLResponseForOperationFileNameFileInfos = $this->findFilesInDirectory(
                $fixtureFolder,
                [$fileName . ':*.json'],
            );
            foreach ($graphQLResponseForOperationFileNameFileInfos as $graphQLResponseForOperationFileInfo) {
                $operationFileName = $graphQLResponseForOperationFileInfo->getFilenameWithoutExtension();
                $operationName = substr($operationFileName, strpos($operationFileName, ':') + 1);
                $providerItems["${dataName}:${operationName}"] = [
                    'application/json',
                    file_get_contents($graphQLResponseFile),
                    $endpoint,
                    [],
                    $query,
                    $variables,
                    $operationName,
                ];
            }
        }
        return $providerItems;
    }

    protected function getEndpoint(): string
    {
        // Admin client endpoint
        return 'wp-admin/edit.php?page=graphql_api&action=execute_query';
    }

    /**
     * Directory under the fixture files are placed
     */
    abstract protected function getFixtureFolder(): string;
}
