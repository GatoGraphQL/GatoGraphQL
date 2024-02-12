<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GraphQLByPoP\GraphQLServer\Unit\FixtureQueryExecutionGraphQLServerTestCaseTrait;
use GraphQLByPoP\GraphQLServer\Unit\FixtureTestCaseTrait;

use function file_get_contents;

abstract class AbstractFixtureUpdateCustomPostBeforeTestWordPressAuthenticatedUserWebserverRequestTestCase extends AbstractUpdateCustomPostBeforeTestWordPressAuthenticatedUserWebserverRequestTestCase
{
    use FixtureTestCaseTrait;
    use FixtureQueryExecutionGraphQLServerTestCaseTrait;

    /**
     * @return array<string,array<string,mixed>> An array of [$fixtureName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."]]
     */
    protected static function getFixtureNameEntries(): array
    {
        $fixtureEntries = [];
        $fixtureFolder = static::getFixtureFolder();

        $graphQLQueryFileNameFileInfos = static::findFilesInDirectory(
            $fixtureFolder,
            ['*.gql'],
            ['*.disabled.gql']
        );

        $fixtureEntries = [];
        foreach ($graphQLQueryFileNameFileInfos as $graphQLQueryFileInfo) {
            $query = $graphQLQueryFileInfo->getContents();

            /**
             * From the GraphQL query file name, generate the remaining file names
             */
            $fileName = $graphQLQueryFileInfo->getFilenameWithoutExtension();
            $filePath = $graphQLQueryFileInfo->getPath();
            $updateEnabledGraphQLResponseFile = static::getGraphQLResponseFile($filePath, $fileName . ':enabled');
            if (!\file_exists($updateEnabledGraphQLResponseFile)) {
                static::throwFileNotExistsException($updateEnabledGraphQLResponseFile);
            }
            $updateDisabledGraphQLResponseFile = static::getGraphQLResponseFile($filePath, $fileName . ':disabled');
            if (!\file_exists($updateDisabledGraphQLResponseFile)) {
                static::throwFileNotExistsException($updateDisabledGraphQLResponseFile);
            }

            $fixtureEntries[$fileName] = [
                'query' => $query,
                'response-enabled' => file_get_contents($updateEnabledGraphQLResponseFile),
                'response-disabled' => file_get_contents($updateDisabledGraphQLResponseFile),
                'endpoint' => static::getFixtureEndpoint($fileName),
            ];
        }
        return static::customizeFixtureEntries($fixtureEntries);
    }

    protected function mustExecuteRESTEndpointToUpdateCustomPost(string $dataName): bool
    {
        return str_ends_with($dataName, ':enabled');
    }

    /**
     * @param array<string,array<string,mixed>> $fixtureEntries
     * @return array<string,array<string,mixed>>
     */
    protected static function customizeFixtureEntries(array $fixtureEntries): array
    {
        return $fixtureEntries;
    }

    protected static function getFixtureEndpoint(string $fileName): ?string
    {
        return static::getEndpoint();
    }
}
