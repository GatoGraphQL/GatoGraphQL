<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GraphQLByPoP\GraphQLServer\Unit\FixtureQueryExecutionGraphQLServerTestCaseTrait;
use RuntimeException;
use stdClass;

use function file_exists;
use function file_get_contents;
use function json_decode;

trait FixtureEndpointWebserverRequestTestCaseTrait
{
    use FixtureQueryExecutionGraphQLServerTestCaseTrait;

    /**
     * Retrieve all GraphQL query files and their expected
     * responses from under the "/fixture" folder
     *
     * @param string $fixtureFolder Source folder for the .gql files and, by default, their .json responses
     * @param string $responseFixtureFolder Possibly define a different folder for the .json responses
     * @return array<string,mixed>
     */
    public static function provideFixtureEndpointEntries(
        string $fixtureFolder,
        ?string $responseFixtureFolder = null,
    ): array {
        $endpoint = static::getEndpoint();

        $responseFixtureFolder ??= $fixtureFolder;

        /**
         * Retrieve all non-disabled GraphQL files
         */
        $graphQLQueryFileNameFileInfos = static::findFilesInDirectory(
            $fixtureFolder,
            ['*.gql'],
            ['*.disabled.gql']
        );

        $providerItems = [];
        foreach ($graphQLQueryFileNameFileInfos as $graphQLQueryFileInfo) {
            // Allow to temporarily disable tests via code
            $fileName = $graphQLQueryFileInfo->getFilenameWithoutExtension();
            if (static::isGraphQLQueryProviderTestDisabled($fileName)) {
                continue;
            }

            $query = $graphQLQueryFileInfo->getContents();

            /**
             * From the GraphQL query file name, generate the remaining file names
             */
            $filePath = $graphQLQueryFileInfo->getPath();
            $graphQLResponseFile = static::getGraphQLResponseFile($filePath, $fileName);
            if (!file_exists($graphQLResponseFile)) {
                static::throwFileNotExistsException($graphQLResponseFile);
            }

            $graphQLVariablesFile = static::getGraphQLVariablesFile($filePath, $fileName);
            $variables = static::getGraphQLVariables($graphQLVariablesFile);

            /**
             * If the test is organized under a subfolder (such as "Success" or "Error"),
             * append it to the named dataset
             */
            $graphQLFilesSubfolder = substr($filePath, strlen($fixtureFolder) + 1);
            $dataName = ($graphQLFilesSubfolder !== '' ? $graphQLFilesSubfolder . \DIRECTORY_SEPARATOR : '') . $fileName;
            $mainFixtureOperationName = static::getMainFixtureOperationName($dataName);
            $providerItems[$dataName] = [
                'application/json',
                file_get_contents($graphQLResponseFile),
                static::getFixtureCustomEndpoint($dataName) ?? $endpoint,
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
            $graphQLResponseForOperationFileNameFileInfos = static::findFilesInDirectory(
                $responseFixtureFolder . ($graphQLFilesSubfolder !== '' ? \DIRECTORY_SEPARATOR . $graphQLFilesSubfolder : ''),
                [$fileName . ':*.json'],
                ['*.disabled.json', '*.var.json'],
            );
            foreach ($graphQLResponseForOperationFileNameFileInfos as $graphQLResponseForOperationFileInfo) {
                $operationFileName = $graphQLResponseForOperationFileInfo->getFilenameWithoutExtension();
                $operationName = substr($operationFileName, strpos($operationFileName, ':') + 1);
                $graphQLVariablesForOperationFile = static::getGraphQLVariablesFile($filePath, $fileName . ':' . $operationName);
                if (\file_exists($graphQLVariablesForOperationFile)) {
                    $graphQLVariablesForOperation = static::getGraphQLVariables($graphQLVariablesForOperationFile);
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
                    static::getFixtureCustomEndpoint($operationDataName) ?? $endpoint,
                    [],
                    $query,
                    $graphQLVariablesForOperation,
                    is_numeric($operationName) ? $mainFixtureOperationName : $operationName,
                ];
            }
        }
        return static::customizeProviderEndpointEntries($providerItems);
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getGraphQLVariables(string $graphQLVariablesFile): array
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

    protected static function getMainFixtureOperationName(string $dataName): ?string
    {
        return null;
    }

    protected static function getFixtureCustomEndpoint(string $dataName): ?string
    {
        return null;
    }

    /**
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected static function customizeProviderEndpointEntries(array $providerItems): array
    {
        return $providerItems;
    }

    abstract protected static function getEndpoint(): string;

    protected static function isGraphQLQueryProviderTestDisabled(string $fileName): bool
    {
        return false;
    }
}
