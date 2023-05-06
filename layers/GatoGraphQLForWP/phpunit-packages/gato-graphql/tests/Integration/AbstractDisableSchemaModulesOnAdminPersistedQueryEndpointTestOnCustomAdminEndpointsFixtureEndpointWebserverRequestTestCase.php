<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\AdminGraphQLEndpointGroups;

abstract class AbstractDisableSchemaModulesOnAdminPersistedQueryEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase
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
