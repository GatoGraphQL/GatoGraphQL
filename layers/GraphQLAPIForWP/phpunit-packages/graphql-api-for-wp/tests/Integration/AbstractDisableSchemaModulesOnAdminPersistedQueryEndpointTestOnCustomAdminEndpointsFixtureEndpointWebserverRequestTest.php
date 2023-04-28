<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\AdminGraphQLEndpointGroups;

abstract class AbstractDisableSchemaModulesOnAdminPersistedQueryEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTest
{
    protected function getEndpoint(): string
    {
        return sprintf(
            '%s&persisted_query_id=%s',
            parent::getEndpoint(),
            $this->getPersistedQueryID()
        );
    }

    abstract protected function getPersistedQueryID(): int;

    protected function getAdminEndpointGroup(): string
    {
        return AdminGraphQLEndpointGroups::PERSISTED_QUERY;
    }
}
