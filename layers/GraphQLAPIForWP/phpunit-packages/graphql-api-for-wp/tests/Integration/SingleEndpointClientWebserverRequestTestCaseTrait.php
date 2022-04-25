<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PoP\Root\Exception\ShouldNotHappenException;

trait SingleEndpointClientWebserverRequestTestCaseTrait
{
    /**
     * To visualize the list of all the modules, and find the "moduleID":
     *
     * @see http://graphql-api.lndo.site/wp-json/graphql-api/v1/admin/modules
     */
    protected function getSingleEndpointClientModuleID(string $dataName): string
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
