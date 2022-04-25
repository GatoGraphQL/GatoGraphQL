<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractClientPathSettingsWebserverRequestTest;
use PoP\Root\Exception\ShouldNotHappenException;

/**
 * Test that updating the path for a client (GraphiQL/Voyager) works well
 */
class ClientPathSettingsWebserverRequestTest extends AbstractClientPathSettingsWebserverRequestTest
{
    /**
     * @return array<string,string[]> Array of 2 elements: [ ${newClientPath}, ${previousClientPath} ]
     */
    protected function provideClientPathEntries(): array
    {
        return [
            'single-endpoint-graphiql' => [
                '/new-graphiql/',
                '/graphiql/',
            ],
            'single-endpoint-voyager' => [
                '/new-schema/',
                '/schema/',
            ],
        ];
    }

    /**
     * To visualize the list of all the modules, and find the "moduleID":
     *
     * @see http://graphql-api.lndo.site/wp-json/graphql-api/v1/admin/modules
     */
    protected function getModuleID(string $dataName): string
    {
        return match ($dataName) {
            'single-endpoint-graphiql' => 'graphqlapi_graphqlapi_graphiql-for-single-endpoint',
            'single-endpoint-voyager' => 'graphqlapi_graphqlapi_interactive-schema-for-single-endpoint',
            default => throw new ShouldNotHappenException(
                sprintf(
                    'There is no moduleID configured for $dataName \'%s\'',
                    $dataName
                )
            )
        };
    }
}
