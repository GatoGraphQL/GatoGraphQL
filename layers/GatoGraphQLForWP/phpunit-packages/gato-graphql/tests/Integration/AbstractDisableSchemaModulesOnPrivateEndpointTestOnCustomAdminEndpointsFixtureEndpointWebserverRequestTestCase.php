<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

abstract class AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase extends AbstractDisableSchemaModulesOnPrivateEndpointsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return sprintf(
            'wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group=%s',
            static::getAdminEndpointGroup()
        );
    }

    abstract protected static function getAdminEndpointGroup(): string;
}
