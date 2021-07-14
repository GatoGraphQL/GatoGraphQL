<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;

/**
 * Cache Control block
 */
class SchemaConfigAccessControlListBlock extends AbstractSchemaConfigCustomPostListBlock
{
    use MainPluginBlockTrait;

    public const ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS = 'accessControlLists';

    protected function getBlockName(): string
    {
        return 'schema-config-access-control-lists';
    }

    public function getSchemaConfigBlockPriority(): int
    {
        return 100;
    }

    public function getEnablingModule(): ?string
    {
        return AccessControlFunctionalityModuleResolver::ACCESS_CONTROL;
    }

    protected function getAttributeName(): string
    {
        return self::ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS;
    }

    protected function getCustomPostType(): string
    {
        /** @var GraphQLAccessControlListCustomPostType */
        $customPostTypeService = $this->instanceManager->getInstance(GraphQLAccessControlListCustomPostType::class);
        return $customPostTypeService->getCustomPostType();
    }

    protected function getHeader(): string
    {
        return \__('Access Control Lists:');
    }
}
