<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\CustomEndpointBlockCategory;

/**
 * Endpoint Options block
 */
class CustomEndpointOptionsBlock extends AbstractEndpointOptionsBlock implements EndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;

    private ?CustomEndpointBlockCategory $customEndpointBlockCategory = null;

    final public function setCustomEndpointBlockCategory(CustomEndpointBlockCategory $customEndpointBlockCategory): void
    {
        $this->customEndpointBlockCategory = $customEndpointBlockCategory;
    }
    final protected function getCustomEndpointBlockCategory(): CustomEndpointBlockCategory
    {
        return $this->customEndpointBlockCategory ??= $this->instanceManager->getInstance(CustomEndpointBlockCategory::class);
    }

    protected function getBlockName(): string
    {
        return 'custom-endpoint-options';
    }

    public function getBlockPriority(): int
    {
        return 160;
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getCustomEndpointBlockCategory();
    }
}
