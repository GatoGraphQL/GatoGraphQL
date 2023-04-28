<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

abstract class AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointsFixtureEndpointWebserverRequestTest
{
    protected function getEndpoint(): string
    {
        return sprintf(
            'wp-admin/edit.php?page=graphql_api&action=execute_query&endpoint_group=%s',
            $this->getAdminEndpointGroup()
        );
    }

    abstract protected function getAdminEndpointGroup(): string;
}
