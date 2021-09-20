<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\CustomEndpointBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractEndpointOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\MainPluginBlockTrait;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EditorHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GeneralUtils;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

/**
 * Endpoint Options block
 */
class CustomEndpointOptionsBlock extends AbstractEndpointOptionsBlock implements EndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;

    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        UserAuthorizationInterface $userAuthorization,
        GeneralUtils $generalUtils,
        EditorHelpers $editorHelpers,
        protected CustomEndpointBlockCategory $customEndpointBlockCategory,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
            $userAuthorization,
            $generalUtils,
            $editorHelpers,
        );
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
