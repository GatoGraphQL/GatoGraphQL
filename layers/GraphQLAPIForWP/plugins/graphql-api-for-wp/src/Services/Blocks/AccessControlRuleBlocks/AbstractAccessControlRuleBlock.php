<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AccessControlBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;

/**
 * Access Control rule block
 */
abstract class AbstractAccessControlRuleBlock extends AbstractBlock
{
    public const ATTRIBUTE_NAME_ACCESS_CONTROL_GROUP = 'accessControlGroup';
    public const ATTRIBUTE_NAME_VALUE = 'value';
    protected AccessControlBlockCategory $accessControlBlockCategory;

    #[Required]
    public function autowireAbstractAccessControlRuleBlock(
        AccessControlBlockCategory $accessControlBlockCategory,
    ): void {
        $this->accessControlBlockCategory = $accessControlBlockCategory;
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->accessControlBlockCategory;
    }
}
