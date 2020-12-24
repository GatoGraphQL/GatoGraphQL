<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLAccessControlListPostType;

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

    protected function getAttributeName(): string
    {
        return self::ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS;
    }

    protected function getPostType(): string
    {
        return GraphQLAccessControlListPostType::POST_TYPE;
    }

    protected function getHeader(): string
    {
        return \__('Access Control Lists:');
    }
}
