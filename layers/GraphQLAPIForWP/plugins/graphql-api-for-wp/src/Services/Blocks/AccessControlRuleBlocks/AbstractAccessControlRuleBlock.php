<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AccessControlBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EditorHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GeneralUtils;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

/**
 * Access Control rule block
 */
abstract class AbstractAccessControlRuleBlock extends AbstractBlock
{
    public const ATTRIBUTE_NAME_ACCESS_CONTROL_GROUP = 'accessControlGroup';
    public const ATTRIBUTE_NAME_VALUE = 'value';

    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        UserAuthorizationInterface $userAuthorization,
        GeneralUtils $generalUtils,
        EditorHelpers $editorHelpers,
        protected AccessControlBlockCategory $accessControlBlockCategory,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
            $userAuthorization,
            $generalUtils,
            $editorHelpers,
        );
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->accessControlBlockCategory;
    }
}
