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

    public final const ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS = 'accessControlLists';

    private ?GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType = null;

    final public function setGraphQLAccessControlListCustomPostType(GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType): void
    {
        $this->graphQLAccessControlListCustomPostType = $graphQLAccessControlListCustomPostType;
    }
    final protected function getGraphQLAccessControlListCustomPostType(): GraphQLAccessControlListCustomPostType
    {
        return $this->graphQLAccessControlListCustomPostType ??= $this->instanceManager->getInstance(GraphQLAccessControlListCustomPostType::class);
    }

    protected function getBlockName(): string
    {
        return 'schema-config-access-control-lists';
    }

    public function getBlockPriority(): int
    {
        return 200;
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
        return $this->getGraphQLAccessControlListCustomPostType()->getCustomPostType();
    }

    protected function getHeader(): string
    {
        return \__('Access Control Lists:');
    }
}
