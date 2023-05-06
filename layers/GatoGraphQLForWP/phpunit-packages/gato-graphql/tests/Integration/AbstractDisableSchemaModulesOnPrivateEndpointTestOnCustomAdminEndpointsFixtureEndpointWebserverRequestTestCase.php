<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

abstract class AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase extends AbstractDisableSchemaModulesOnPrivateEndpointsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return sprintf(
            'wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group=%s',
            $this->getAdminEndpointGroup()
        );
    }

    abstract protected function getAdminEndpointGroup(): string;
}
