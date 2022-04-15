<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\WebserverRequests;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractWebserverRequestTestCase;

class LocalhostWordPressAuthenticatedUserWebserverRequestTest extends AbstractWebserverRequestTestCase
{
    use LocalhostWebserverRequestTestTrait;
    use LocalhostWordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        return [
            'admin-client' => [
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
