<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

abstract class AbstractGraphQLSpecComplianceModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_graphql-spec-compliance';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return true;
    }
}
