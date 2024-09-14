<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MutationSchemaTypeModuleResolver;

abstract class AbstractTreatAuthorInputInCustomPostMutationAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getSettingsKey(): string
    {
        return MutationSchemaTypeModuleResolver::OPTION_TREAT_AUTHOR_INPUT_IN_CUSTOMPOST_MUTATION_AS_SENSITIVE_DATA;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-custompost-user-mutations';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }
}
