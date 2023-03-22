<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

class SchemaCustomPostTagMutationsSchemaConfigurationExecuter extends AbstractSchemaMutationsSchemaConfigurationExecuter
{
    public function getHookModuleClass(): string
    {
        return \PoPCMSSchema\CustomPostTagMutations\Module::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return \PoPCMSSchema\CustomPostTagMutations\Environment::USE_PAYLOADABLE_CUSTOMPOSTTAG_MUTATIONS;
    }
}
