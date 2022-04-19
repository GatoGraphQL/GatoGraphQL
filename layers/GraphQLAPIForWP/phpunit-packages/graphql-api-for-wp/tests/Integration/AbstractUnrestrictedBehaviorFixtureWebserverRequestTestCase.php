<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLByPoP\GraphQLServer\Standalone\FixtureTestCaseTrait;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractEndpointWebserverRequestTestCase;
use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

use function file_get_contents;

abstract class AbstractUnrestrictedBehaviorFixtureWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
{
    use FixtureTestCaseTrait;
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * Retrieve all files under the "/fixture" folder
     * to retrieve the GraphQL queries, and their 
     * expected responses:
     * 
     * - ${fileName}-access-granted.json: when access to the "unrestricted" field is granted
     * - ${fileName}-access-forbidden.json: when access to the "unrestricted" field is forbidden
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
            $grantedAccessGraphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '-access-granted.json';
            if (!\file_exists($grantedAccessGraphQLResponseFile)) {
                $this->throwFileNotExistsException($grantedAccessGraphQLResponseFile);
            }
            $forbiddenAccessGraphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '-access-forbidden.json';
            if (!\file_exists($forbiddenAccessGraphQLResponseFile)) {
                $this->throwFileNotExistsException($forbiddenAccessGraphQLResponseFile);
            }

            $dataName = $fileName;
            $providerItems[$dataName . '-access-granted'] = [
                'application/json',
                file_get_contents($grantedAccessGraphQLResponseFile),
                $accessGrantedEndpoint,
                [],
                $query,
            ];
            $providerItems[$dataName . '-access-forbidden'] = [
                'application/json',
                file_get_contents($forbiddenAccessGraphQLResponseFile),
                $accessForbiddenEndpoint,
                [],
                $query,
            ];
        }
        return $providerItems;
    }

    /**
     * Directory under the fixture files are placed
     */
    abstract protected function getFixtureFolder(): string;
}
