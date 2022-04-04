<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AccessControlBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;

/**
 * Access Control rule block
 */
abstract class AbstractAccessControlRuleBlock extends AbstractBlock
{
    public final const ATTRIBUTE_NAME_ACCESS_CONTROL_GROUP = 'accessControlGroup';
    public final const ATTRIBUTE_NAME_VALUE = 'value';

    private ?AccessControlBlockCategory $accessControlBlockCategory = null;

    final public function setAccessControlBlockCategory(AccessControlBlockCategory $accessControlBlockCategory): void
    {
        $this->accessControlBlockCategory = $accessControlBlockCategory;
    }
    final protected function getAccessControlBlockCategory(): AccessControlBlockCategory
    {
        return $this->accessControlBlockCategory ??= $this->instanceManager->getInstance(AccessControlBlockCategory::class);
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getAccessControlBlockCategory();
    }
}
