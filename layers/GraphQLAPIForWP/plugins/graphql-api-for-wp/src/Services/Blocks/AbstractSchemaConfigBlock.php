<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\SchemaConfigurationBlockCategory;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractSchemaConfigBlock extends AbstractBlock implements SchemaConfigEditorBlockServiceTagInterface
{
    protected SchemaConfigurationBlockCategory $schemaConfigurationBlockCategory;

    #[Required]
    public function autowireAbstractSchemaConfigBlock(
        SchemaConfigurationBlockCategory $schemaConfigurationBlockCategory,
    ): void {
        $this->schemaConfigurationBlockCategory = $schemaConfigurationBlockCategory;
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->schemaConfigurationBlockCategory;
    }

    public function getBlockPriority(): int
    {
        return 10;
    }
}
