<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GraphQLByPoP\GraphQLServer\Unit\FixtureQueryExecutionGraphQLServerTestCaseTrait;
use PHPUnitForGatoGraphQL\WebserverRequests\AbstractEndpointWebserverRequestTestCase;
use RuntimeException;
use stdClass;

use function file_exists;
use function file_get_contents;
use function json_decode;

abstract class AbstractFixtureEndpointWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
{
    use FixtureQueryExecutionGraphQLServerTestCaseTrait;

    public function getDataSetAsString(bool $includeData = true): string
    {
        return $this->addFixtureFolderInfo(parent::getDataSetAsString($includeData));
    }

    /**
     * Retrieve all GraphQL query files and their expected
     * responses from under the "/fixture" folder
     */
    final protected function provideEndpointEntries(): array
    {
        $endpoint = $this->getEndpoint();

        /**
         * Source folder for the .gql files and,
         * by default, their .json responses
         */
        $fixtureFolder = $this->getFixtureFolder();

        /**
         * Possibly define a different folder for the .json responses
         */
        $responseFixtureFolder = $this->getResponseFixtureFolder();

        /**
         * Retrieve all non-disabled GraphQL files
         */
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
            $graphQLResponseFile = $this->getGraphQLResponseFile($filePath, $fileName);
            if (!file_exists($graphQLResponseFile)) {
                $this->throwFileNotExistsException($graphQLResponseFile);
            }

            $graphQLVariablesFile = $this->getGraphQLVariablesFile($filePath, $fileName);
            $variables = $this->getGraphQLVariables($graphQLVariablesFile);

            /**
             * If the test is organized under a subfolder (such as "Success" or "Error"),
             * append it to the named dataset
             */
            $graphQLFilesSubfolder = substr($filePath, strlen($fixtureFolder) + 1);
            $dataName = ($graphQLFilesSubfolder !== '' ? $graphQLFilesSubfolder . \DIRECTORY_SEPARATOR : '') . $fileName;
            $mainFixtureOperationName = $this->getMainFixtureOperationName($dataName);
            $providerItems[$dataName] = [
                'application/json',
                file_get_contents($graphQLResponseFile),
                $endpoint,
                [],
                $query,
                $variables,
                $mainFixtureOperationName,
            ];

            /**
             * Retrieve additional GraphQL responses to execute some "operationName".
             * If it is numeric (eg: "user-properties:2.json") then it's not an
             * operation name, but simply the re-execution of the same query, that will
             * produce a different response (eg: by not executing `setUp` and `tearDown`)
             */
            $graphQLResponseForOperationFileNameFileInfos = $this->findFilesInDirectory(
                $responseFixtureFolder . ($graphQLFilesSubfolder !== '' ? \DIRECTORY_SEPARATOR . $graphQLFilesSubfolder : ''),
                [$fileName . ':*.json'],
                ['*.disabled.json', '*.var.json'],
            );
            foreach ($graphQLResponseForOperationFileNameFileInfos as $graphQLResponseForOperationFileInfo) {
                $operationFileName = $graphQLResponseForOperationFileInfo->getFilenameWithoutExtension();
                $operationName = substr($operationFileName, strpos($operationFileName, ':') + 1);
                $graphQLVariablesForOperationFile = $this->getGraphQLVariablesFile($filePath, $fileName . ':' . $operationName);
                if (\file_exists($graphQLVariablesForOperationFile)) {
                    $graphQLVariablesForOperation = $this->getGraphQLVariables($graphQLVariablesForOperationFile);
                } else {
                    $graphQLVariablesForOperation = $variables;
                }

                $operationDataName = sprintf(
                    '%s:%s',
                    $dataName,
                    $operationName
                );
                $providerItems[$operationDataName] = [
                    'application/json',
                    $graphQLResponseForOperationFileInfo->getContents(),
                    $endpoint,
                    [],
                    $query,
                    $graphQLVariablesForOperation,
                    is_numeric($operationName) ? $mainFixtureOperationName : $operationName,
                ];
            }
        }
        return $this->customizeProviderEndpointEntries($providerItems);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getGraphQLVariables(string $graphQLVariablesFile): array
    {
        if (!file_exists($graphQLVariablesFile)) {
            return [];
        }

        $fileContents = file_get_contents($graphQLVariablesFile);
        if ($fileContents === false) {
            throw new RuntimeException(
                sprintf(
                    'File "%s" cannot be read',
                    $graphQLVariablesFile
                )
            );
        }
        $variables = json_decode($fileContents);
        if (!is_array($variables) && !($variables instanceof stdClass)) {
            throw new RuntimeException(
                sprintf(
                    'Decoding the JSON inside file "%s" failed',
                    $graphQLVariablesFile
                )
            );
        }
        return (array) $variables;
    }

    protected function getMainFixtureOperationName(string $dataName): ?string
    {
        return null;
    }

    /**
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        return $providerItems;
    }

    abstract protected function getEndpoint(): string;

    protected function isGraphQLQueryProviderTestDisabled(string $fileName): bool
    {
        return false;
    }
}
