<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

class SchemaCommentMetaMutationsBlockSchemaConfigurationExecuter extends AbstractSchemaMutationsBlockSchemaConfigurationExecuter
{
    public function getHookModuleClass(): string
    {
        return \PoPCMSSchema\CommentMetaMutations\Module::class;
    }

    public function getHookUsePayloadableEnvironmentClass(): string
    {
        return \PoPCMSSchema\CommentMetaMutations\Environment::USE_PAYLOADABLE_COMMENT_META_MUTATIONS;
    }

    public function getHookAddFieldsToQueryPayloadableEnvironmentClass(): string
    {
        return \PoPCMSSchema\CommentMetaMutations\Environment::ADD_FIELDS_TO_QUERY_PAYLOADABLE_COMMENT_META_MUTATIONS;
    }
}
