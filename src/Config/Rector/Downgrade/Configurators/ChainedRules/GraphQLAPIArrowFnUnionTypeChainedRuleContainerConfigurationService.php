<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use PoP\PoP\Config\Rector\Downgrade\Configurators\GraphQLAPIContainerConfigurationServiceTrait;

class GraphQLAPIArrowFnUnionTypeChainedRuleContainerConfigurationService extends AbstractPluginArrowFnUnionTypeChainedRuleContainerConfigurationService
{
    use GraphQLAPIContainerConfigurationServiceTrait;

    protected function getPaths(): array
    {
        return [
            $this->pluginDir . '/vendor/getpop/component-model/src/TypeResolvers/AbstractRelationalTypeResolver.php',
            $this->pluginDir . '/vendor/getpop/component-model/src/TypeResolvers/ObjectType/AbstractObjectTypeResolver.php',
            $this->pluginDir . '/vendor/getpop/engine/src/DirectiveResolvers/FilterIDsSatisfyingConditionDirectiveResolverTrait.php',
            $this->pluginDir . '/vendor/pop-cms-schema/menus/src/RelationalTypeDataLoaders/ObjectType/MenuItemTypeDataLoader.php',
            $this->pluginDir . '/vendor/pop-cms-schema/menus/src/RelationalTypeDataLoaders/ObjectType/MenuTypeDataLoader.php',
            $this->pluginDir . '/vendor/pop-cms-schema/user-avatars/src/RelationalTypeDataLoaders/ObjectType/UserAvatarTypeDataLoader.php',
            $this->pluginDir . '/vendor/pop-wp-schema/menus/src/FieldResolvers/ObjectType/MenuObjectTypeFieldResolver.php',
        ];
    }
}
