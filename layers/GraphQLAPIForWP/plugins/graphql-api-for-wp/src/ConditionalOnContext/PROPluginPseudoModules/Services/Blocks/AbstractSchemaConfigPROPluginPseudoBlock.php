<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginPseudoModules\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\SchemaConfigurationBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigEditorBlockServiceTagInterface;

abstract class AbstractSchemaConfigPROPluginPseudoBlock extends AbstractPROPluginPseudoBlock implements SchemaConfigEditorBlockServiceTagInterface
{
    private ?SchemaConfigurationBlockCategory $schemaConfigurationBlockCategory = null;

    final public function setSchemaConfigurationBlockCategory(SchemaConfigurationBlockCategory $schemaConfigurationBlockCategory): void
    {
        $this->schemaConfigurationBlockCategory = $schemaConfigurationBlockCategory;
    }
    final protected function getSchemaConfigurationBlockCategory(): SchemaConfigurationBlockCategory
    {
        /** @var SchemaConfigurationBlockCategory */
        return $this->schemaConfigurationBlockCategory ??= $this->instanceManager->getInstance(SchemaConfigurationBlockCategory::class);
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
