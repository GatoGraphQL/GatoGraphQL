<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AccessControlBlockCategory;

/**
 * Access Control rule block
 */
abstract class AbstractAccessControlRuleBlock extends AbstractBlock
{
    public const ATTRIBUTE_NAME_ACCESS_CONTROL_GROUP = 'accessControlGroup';
    public const ATTRIBUTE_NAME_VALUE = 'value';

    protected function getBlockCategoryClass(): ?string
    {
        return AccessControlBlockCategory::class;
    }
}
