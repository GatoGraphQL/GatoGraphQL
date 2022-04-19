<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractWebserverRequestTestCase;
use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

class WordPressAuthenticatedUserIntegrationWebserverRequestTest extends AbstractWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        return [
            'admin-client' => [
                'application/json',
                <<<JSON
                {
                    "data": {
                        "id": "root"
                    }
                }
                JSON,
                'wp-admin/edit.php?page=graphql_api&action=execute_query',
                [],
                <<<GRAPHQL
                {
                    id
                }
                GRAPHQL,
            ],
        ];
    }
}
