<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLByPoP\GraphQLServer\Unit\FixtureTestCaseTrait;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractEndpointWebserverRequestTestCase;

use function file_get_contents;
use function file_exists;
use function json_decode;

abstract class AbstractFixtureEndpointWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
{
    use FixtureTestCaseTrait;
    
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
            // Allow to temporarily disable tests via code
            $fileName = $graphQLQueryFileInfo->getFilenameWithoutExtension();
            if ($this->isGraphQLQueryProviderTestDisabled($fileName)) {
                continue;
            }

            $query = $graphQLQueryFileInfo->getContents();

            /**
             * From the GraphQL query file name, generate the remaining file names
             */
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
                ['*.disabled.json'],
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

    abstract protected function getEndpoint(): string;

    protected function isGraphQLQueryProviderTestDisabled(string $fileName): bool
    {
        return false;
    }

    /**
     * Directory under the fixture files are placed
     */
    abstract protected function getFixtureFolder(): string;
}
