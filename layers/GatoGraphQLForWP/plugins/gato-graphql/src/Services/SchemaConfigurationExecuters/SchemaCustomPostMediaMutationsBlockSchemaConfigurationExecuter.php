<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

class SchemaCustomPostMediaMutationsBlockSchemaConfigurationExecuter extends AbstractSchemaMutationsBlockSchemaConfigurationExecuter
{
    public function getHookModuleClass(): string
    {
        return \PoPCMSSchema\CustomPostMediaMutations\Module::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return \PoPCMSSchema\CustomPostMediaMutations\Environment::USE_PAYLOADABLE_CUSTOMPOSTMEDIA_MUTATIONS;
    }
}
