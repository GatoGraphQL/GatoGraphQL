<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

class SchemaMediaMutationsBlockSchemaConfigurationExecuter extends AbstractSchemaMutationsBlockSchemaConfigurationExecuter
{
    public function getHookModuleClass(): string
    {
        return \PoPCMSSchema\MediaMutations\Module::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return \PoPCMSSchema\MediaMutations\Environment::USE_PAYLOADABLE_MEDIA_MUTATIONS;
    }
}
