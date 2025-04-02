<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

class SchemaUserMetaMutationsBlockSchemaConfigurationExecuter extends AbstractSchemaMutationsBlockSchemaConfigurationExecuter
{
    public function getHookModuleClass(): string
    {
        return \PoPCMSSchema\UserMetaMutations\Module::class;
    }

    public function getHookUsePayloadableEnvironmentClass(): string
    {
        return \PoPCMSSchema\UserMetaMutations\Environment::USE_PAYLOADABLE_USER_META_MUTATIONS;
    }

    public function getHookAddFieldsToQueryPayloadableEnvironmentClass(): string
    {
        return \PoPCMSSchema\UserMetaMutations\Environment::ADD_FIELDS_TO_QUERY_PAYLOADABLE_USER_META_MUTATIONS;
    }
}
