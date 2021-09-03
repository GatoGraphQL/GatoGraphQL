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
            $this->pluginDir . '/vendor/getpop/engine/src/DirectiveResolvers/FilterIDsSatisfyingConditionDirectiveResolverTrait.php',
            $this->pluginDir . '/vendor/pop-schema/menus/src/RelationalTypeDataLoaders/Object/MenuItemTypeDataLoader.php',
            $this->pluginDir . '/vendor/pop-schema/menus/src/RelationalTypeDataLoaders/Object/MenuTypeDataLoader.php',
            $this->pluginDir . '/vendor/pop-schema/user-avatars/src/RelationalTypeDataLoaders/Object/UserAvatarTypeDataLoader.php',
            $this->pluginDir . '/vendor/pop-wp-schema/menus/src/FieldResolvers/MenuFieldResolver.php',
        ];
    }
}
