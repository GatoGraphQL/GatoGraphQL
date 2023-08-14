<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\BlockCategories\BlockCategoryInterface;
use GatoGraphQL\GatoGraphQL\Services\BlockCategories\SchemaConfigurationBlockCategory;

abstract class AbstractSchemaConfigBlock extends AbstractBlock implements SchemaConfigEditorBlockServiceTagInterface
{
    private ?SchemaConfigurationBlockCategory $schemaConfigurationBlockCategory = null;

    final public function setSchemaConfigurationBlockCategory(SchemaConfigurationBlockCategory $schemaConfigurationBlockCategory): void
    {
        $this->schemaConfigurationBlockCategory = $schemaConfigurationBlockCategory;
    }
    final protected function getSchemaConfigurationBlockCategory(): SchemaConfigurationBlockCategory
    {
        if ($this->schemaConfigurationBlockCategory === null) {
            /** @var SchemaConfigurationBlockCategory */
            $schemaConfigurationBlockCategory = $this->instanceManager->getInstance(SchemaConfigurationBlockCategory::class);
            $this->schemaConfigurationBlockCategory = $schemaConfigurationBlockCategory;
        }
        return $this->schemaConfigurationBlockCategory;
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getSchemaConfigurationBlockCategory();
    }

    public function getBlockPriority(): int
    {
        return 10;
    }
}
