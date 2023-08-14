<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PoP\Root\Exception\ShouldNotHappenException;

trait SingleEndpointClientWebserverRequestTestCaseTrait
{
    /**
     * To visualize the list of all the modules, and find the "moduleID":
     *
     * @see https://gatographql.lndo.site/wp-json/gatographql/v1/admin/modules
     */
    protected function getSingleEndpointClientModuleID(string $dataName): string
    {
        return match ($dataName) {
            'single-endpoint-graphiql' => 'gatographql_gatographql_graphiql-for-single-endpoint',
            'single-endpoint-voyager' => 'gatographql_gatographql_interactive-schema-for-single-endpoint',
            default => throw new ShouldNotHappenException(
                sprintf(
                    'There is no moduleID configured for $dataName \'%s\'',
                    $dataName
                )
            )
        };
    }
}
