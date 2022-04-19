<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use function file_get_contents;
use GraphQLByPoP\GraphQLServer\Standalone\FixtureTestCaseTrait;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractWebserverRequestTestCase;

use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;
use RuntimeException;

abstract class AbstractUnrestrictedBehaviorFixtureWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    use FixtureTestCaseTrait;
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * Retrieve all files under the "/fixture" folder
     * to retrieve the GraphQL queries, and their 
     * expected responses:
     * 
     * - ${fileName}-valid.json: when access to the "unrestricted" field is granted
     * - ${fileName}-invalid.json: when access to the "unrestricted" field is forbidden
     */
    final protected function provideEndpointEntries(): array
    {
        $accessGrantedEndpoint = 'wp-admin/edit.php?page=graphql_api&action=execute_query&behavior=unrestricted';
        $accessForbiddenEndpoint = 'wp-admin/edit.php?page=graphql_api&action=execute_query';

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
            $validGraphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '-valid.json';
            if (!\file_exists($validGraphQLResponseFile)) {
                $this->throwFileNotExistsException($validGraphQLResponseFile);
            }
            $invalidGraphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '-invalid.json';
            if (!\file_exists($invalidGraphQLResponseFile)) {
                $this->throwFileNotExistsException($invalidGraphQLResponseFile);
            }

            $dataName = $fileName;
            $providerItems[$dataName . '-valid'] = [
                'application/json',
                file_get_contents($validGraphQLResponseFile),
                $accessGrantedEndpoint,
                [],
                $query,
            ];
            $providerItems[$dataName . '-invalid'] = [
                'application/json',
                file_get_contents($invalidGraphQLResponseFile),
                $accessForbiddenEndpoint,
                [],
                $query,
            ];
        }
        return $providerItems;
    }

    /**
     * @throws RuntimeException
     */
    protected function throwFileNotExistsException(string $file): never
    {
        throw new RuntimeException(
            sprintf(
                'File "%s" does not exist',
                $file
            )
        );
    }

    /**
     * Directory under the fixture files are placed
     */
    abstract protected function getFixtureFolder(): string;
}
