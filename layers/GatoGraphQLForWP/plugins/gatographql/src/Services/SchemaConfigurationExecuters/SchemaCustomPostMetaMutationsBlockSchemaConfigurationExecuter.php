<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

class SchemaCustomPostMetaMutationsBlockSchemaConfigurationExecuter extends AbstractSchemaMutationsBlockSchemaConfigurationExecuter
{
    public function getHookModuleClass(): string
    {
        return \PoPCMSSchema\CustomPostMetaMutations\Module::class;
    }

    public function getHookUsePayloadableEnvironmentClass(): string
    {
        return \PoPCMSSchema\CustomPostMetaMutations\Environment::USE_PAYLOADABLE_CUSTOMPOST_META_MUTATIONS;
    }

    public function getHookAddFieldsToQueryPayloadableEnvironmentClass(): string
    {
        return \PoPCMSSchema\CustomPostMetaMutations\Environment::ADD_FIELDS_TO_QUERY_PAYLOADABLE_CUSTOMPOST_META_MUTATIONS;
    }
}
