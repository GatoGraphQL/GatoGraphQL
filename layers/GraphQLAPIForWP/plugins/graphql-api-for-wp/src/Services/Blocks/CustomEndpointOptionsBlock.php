<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\CustomEndpointBlockCategory;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Endpoint Options block
 */
class CustomEndpointOptionsBlock extends AbstractEndpointOptionsBlock implements EndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;

    protected CustomEndpointBlockCategory $customEndpointBlockCategory;

    #[Required]
    public function autowireCustomEndpointOptionsBlock(
        CustomEndpointBlockCategory $customEndpointBlockCategory,
    ): void {
        $this->customEndpointBlockCategory = $customEndpointBlockCategory;
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
        return $this->customEndpointBlockCategory;
    }
}
