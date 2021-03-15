<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;

/**
 * Cache Control block
 */
class SchemaConfigAccessControlListBlock extends AbstractSchemaConfigPostListBlock
{
    public const ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS = 'accessControlLists';

    protected function getBlockName(): string
    {
        return 'schema-config-access-control-lists';
    }

    public function getEnablingModule(): ?string
    {
        return AccessControlFunctionalityModuleResolver::ACCESS_CONTROL;
    }

    protected function getAttributeName(): string
    {
        return self::ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS;
    }

    protected function getCustomPostType(): string
    {
        return GraphQLAccessControlListCustomPostType::CUSTOM_POST_TYPE;
    }

    protected function getHeader(): string
    {
        return \__('Access Control Lists:');
    }
}
