<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\VersioningFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\GraphQLFieldDeprecationListPostType;

/**
 * Cache Control block
 */
class SchemaConfigFieldDeprecationListBlock extends AbstractSchemaConfigPostListBlock
{
    public const ATTRIBUTE_NAME_FIELD_DEPRECATION_LISTS = 'fieldDeprecationLists';

    protected function getBlockName(): string
    {
        return 'schema-config-field-deprecation-lists';
    }

    public function getEnablingModule(): ?string
    {
        return VersioningFunctionalityModuleResolver::FIELD_DEPRECATION;
    }

    protected function getAttributeName(): string
    {
        return self::ATTRIBUTE_NAME_FIELD_DEPRECATION_LISTS;
    }

    protected function getPostType(): string
    {
        return GraphQLFieldDeprecationListPostType::POST_TYPE;
    }

    protected function getHeader(): string
    {
        return \__('Field Deprecation Lists:');
    }
}
