<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\SchemaConfigurationBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EditorHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GeneralUtils;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractSchemaConfigBlock extends AbstractBlock implements SchemaConfigEditorBlockServiceTagInterface
{
    protected SchemaConfigurationBlockCategory $schemaConfigurationBlockCategory;

    #[Required]
    public function autowireAbstractSchemaConfigBlock(
        SchemaConfigurationBlockCategory $schemaConfigurationBlockCategory,
    ) {
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
