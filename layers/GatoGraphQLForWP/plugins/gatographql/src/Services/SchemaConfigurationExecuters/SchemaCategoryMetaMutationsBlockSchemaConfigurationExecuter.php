<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

class SchemaCategoryMetaMutationsBlockSchemaConfigurationExecuter extends AbstractSchemaMutationsBlockSchemaConfigurationExecuter
{
    public function getHookModuleClass(): string
    {
        return \PoPCMSSchema\CategoryMetaMutations\Module::class;
    }

    public function getHookUsePayloadableEnvironmentClass(): string
    {
        return \PoPCMSSchema\CategoryMetaMutations\Environment::USE_PAYLOADABLE_CATEGORY_META_MUTATIONS;
    }

    public function getHookAddFieldsToQueryPayloadableEnvironmentClass(): string
    {
        return \PoPCMSSchema\CategoryMetaMutations\Environment::ADD_FIELDS_TO_QUERY_PAYLOADABLE_CATEGORY_META_MUTATIONS;
    }
}
