<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GraphQLByPoP\GraphQLServer\Unit\FixtureTestCaseTrait;
use PHPUnitForGatoGraphQL\WebserverRequests\AbstractEndpointWebserverRequestTestCase;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

use function file_get_contents;

abstract class AbstractUnrestrictedBehaviorFixtureWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
{
    use FixtureTestCaseTrait;
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * Since PHPUnit v10, this is not possible anymore!
     */
    // final public function dataSetAsString(): string
    // {
    //     return $this->addFixtureFolderInfo(parent::dataSetAsString());
    // }

    /**
     * Retrieve all files under the "/fixture" folder
     * to retrieve the GraphQL queries, and their
     * expected responses:
     *
     * - ${fileName}-access-granted.json: when access to the "unrestricted" field is granted
     * - ${fileName}-access-forbidden.json: when access to the "unrestricted" field is forbidden
     */
    final public static function provideEndpointEntries(): array
    {
        $accessGrantedEndpoint = 'wp-admin/edit.php?page=gatographql&action=execute_query&endpoint_group=pluginOwnUse';
        $accessForbiddenEndpoint = 'wp-admin/edit.php?page=gatographql&action=execute_query';

        $fixtureFolder = static::getFixtureFolder();
        $graphQLQueryFileNameFileInfos = static::findFilesInDirectory(
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
                static::throwFileNotExistsException($grantedAccessGraphQLResponseFile);
            }
            $forbiddenAccessGraphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '-access-forbidden.json';
            if (!\file_exists($forbiddenAccessGraphQLResponseFile)) {
                static::throwFileNotExistsException($forbiddenAccessGraphQLResponseFile);
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
}
