<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLByPoP\GraphQLServer\Standalone\FixtureTestCaseTrait;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractWebserverRequestTestCase;
use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

use function file_get_contents;

abstract class AbstractQueryExecutionFixtureWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    use FixtureTestCaseTrait;
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * Retrieve all GraphQL query files and their expected
     * responses from under the "/fixture" folder
     */
    final protected function provideEndpointEntries(): array
    {
        $endpoint = 'wp-admin/edit.php?page=graphql_api&action=execute_query';

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
            if (!\file_exists($graphQLResponseFile)) {
                $this->throwFileNotExistsException($graphQLResponseFile);
            }

            $dataName = $fileName;
            $providerItems[$dataName] = [
                'application/json',
                file_get_contents($graphQLResponseFile),
                $endpoint,
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
