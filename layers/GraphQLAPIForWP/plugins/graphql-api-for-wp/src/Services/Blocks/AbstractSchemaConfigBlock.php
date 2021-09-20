<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\SchemaConfigurationBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EditorHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GeneralUtils;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractSchemaConfigBlock extends AbstractBlock implements SchemaConfigEditorBlockServiceTagInterface
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        UserAuthorizationInterface $userAuthorization,
        GeneralUtils $generalUtils,
        EditorHelpers $editorHelpers,
        protected SchemaConfigurationBlockCategory $schemaConfigurationBlockCategory,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
            $userAuthorization,
            $generalUtils,
            $editorHelpers,
        );
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
