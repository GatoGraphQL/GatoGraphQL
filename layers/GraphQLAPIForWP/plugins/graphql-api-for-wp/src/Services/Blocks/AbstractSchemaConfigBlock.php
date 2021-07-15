<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\SchemaConfigurationBlockCategory;

abstract class AbstractSchemaConfigBlock extends AbstractBlock implements SchemaConfigEditorBlockServiceTagInterface
{
    protected function isDynamicBlock(): bool
    {
        return true;
    }

    protected function getBlockCategoryClass(): ?string
    {
        return SchemaConfigurationBlockCategory::class;
    }

    public function getBlockPriority(): int
    {
        return 10;
    }
}
