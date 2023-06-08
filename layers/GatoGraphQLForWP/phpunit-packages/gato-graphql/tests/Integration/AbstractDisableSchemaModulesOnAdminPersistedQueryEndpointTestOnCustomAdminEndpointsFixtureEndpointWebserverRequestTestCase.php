<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\AdminGraphQLEndpointGroups;

abstract class AbstractDisableSchemaModulesOnAdminPersistedQueryEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return sprintf(
            '%s&persisted_query_id=%s',
            parent::getEndpoint(),
            static::getPersistedQueryID()
        );
    }

    abstract protected static function getPersistedQueryID(): int;

    protected static function getAdminEndpointGroup(): string
    {
        return AdminGraphQLEndpointGroups::PERSISTED_QUERY;
    }
}
