<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLByPoP\GraphQLServer\Unit\FixtureTestCaseTrait;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractEndpointWebserverRequestTestCase;
use RuntimeException;

use function file_exists;
use function file_get_contents;
use function json_decode;

abstract class AbstractFixtureEndpointWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
{
    use FixtureTestCaseTrait;

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
            $graphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.json';
            if ($responseFixtureFolder !== $fixtureFolder) {
                $graphQLResponseFile = str_replace(
                    $fixtureFolder,
                    $responseFixtureFolder,
                    $graphQLResponseFile
                );
            }
            if (!file_exists($graphQLResponseFile)) {
                $this->throwFileNotExistsException($graphQLResponseFile);
            }

            $variables = [];
            $graphQLVariablesFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.var.json';
            if ($responseFixtureFolder !== $fixtureFolder) {
                $graphQLVariablesFile = str_replace(
                    $fixtureFolder,
                    $responseFixtureFolder,
                    $graphQLVariablesFile
                );
            }
            if (file_exists($graphQLVariablesFile)) {
                $fileContents = file_get_contents($graphQLVariablesFile);
                if ($fileContents === false) {
                    throw new RuntimeException(
                        sprintf(
                            'File "%s" cannot be read',
                            $graphQLVariablesFile
                        )
                    );
                }
                $variables = json_decode(
                    $fileContents,
                    true
                );
                if (!is_array($variables)) {
                    throw new RuntimeException(
                        sprintf(
                            'Decoding the JSON inside file "%s" failed',
                            $graphQLVariablesFile
                        )
                    );
                }
            }

            /**
             * If the test is organized under a subfolder (such as "Success" or "Error"),
             * append it to the named dataset
             */
            $graphQLFilesSubfolder = substr($filePath, strlen($responseFixtureFolder) + 1);
            $dataName = ($graphQLFilesSubfolder !== '' ? $graphQLFilesSubfolder . \DIRECTORY_SEPARATOR : '') . $fileName;
            $providerItems[$dataName] = [
                'application/json',
                file_get_contents($graphQLResponseFile),
                $endpoint,
                [],
                $query,
                $variables,
                $this->getMainFixtureOperationName(),
            ];

            /**
             * Retrieve additional GraphQL responses to execute some "operationName".
             * If it is numeric (eg: "user-properties:2.json") then it's not an
             * operation name, but simply the re-execution of the same query, that will
             * produce a different response (eg: by not executing `setUp` and `tearDown`)
             */
            $graphQLResponseForOperationFileNameFileInfos = $this->findFilesInDirectory(
                $responseFixtureFolder,
                [$fileName . ':*.json'],
                ['*.disabled.json'],
            );
            foreach ($graphQLResponseForOperationFileNameFileInfos as $graphQLResponseForOperationFileInfo) {
                $operationFileName = $graphQLResponseForOperationFileInfo->getFilenameWithoutExtension();
                $operationName = substr($operationFileName, strpos($operationFileName, ':') + 1);
                $providerItems["${dataName}:${operationName}"] = [
                    'application/json',
                    $graphQLResponseForOperationFileInfo->getContents(),
                    $endpoint,
                    [],
                    $query,
                    $variables,
                    is_numeric($operationName) ? '' : $operationName,
                ];
            }
        }
        return $this->customizeProviderEndpointEntries($providerItems);
    }

    protected function getMainFixtureOperationName(): ?string
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
