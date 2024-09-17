<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver;
use PHPUnitForGatoGraphQL\WebserverRequests\Environment as WebserverRequestsEnvironment;

abstract class AbstractTreatCustomPostEditURLAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getSettingsKey(): string
    {
        return SchemaTypeModuleResolver::OPTION_TREAT_CUSTOMPOST_EDIT_URL_AS_SENSITIVE_DATA;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-customposts';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }

    protected function adaptResponseBody(string $responseBody): string
    {
        return str_replace(
            WebserverRequestsEnvironment::getIntegrationTestsWebserverDomain(),
            'gatographql.lndo.site',
            parent::adaptResponseBody($responseBody)
        );
    }
}
