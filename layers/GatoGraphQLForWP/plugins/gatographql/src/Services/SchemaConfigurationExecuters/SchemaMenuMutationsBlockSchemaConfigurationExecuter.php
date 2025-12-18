<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

class SchemaMenuMutationsBlockSchemaConfigurationExecuter extends AbstractSchemaMutationsBlockSchemaConfigurationExecuter
{
    public function getHookModuleClass(): string
    {
        return \PoPCMSSchema\MenuMutations\Module::class;
    }

    public function getHookUsePayloadableEnvironmentClass(): string
    {
        return \PoPCMSSchema\MenuMutations\Environment::USE_PAYLOADABLE_MENU_MUTATIONS;
    }

    public function getHookAddFieldsToQueryPayloadableEnvironmentClass(): string
    {
        return \PoPCMSSchema\MenuMutations\Environment::ADD_FIELDS_TO_QUERY_PAYLOADABLE_MENU_MUTATIONS;
    }
}
