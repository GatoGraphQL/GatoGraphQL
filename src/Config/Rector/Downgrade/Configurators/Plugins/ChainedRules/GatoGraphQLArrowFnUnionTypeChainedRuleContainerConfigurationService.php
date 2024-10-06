<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\Plugins\ChainedRules;

use PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules\AbstractPluginArrowFnUnionTypeChainedRuleContainerConfigurationService;
use PoP\PoP\Config\Rector\Downgrade\Configurators\Plugins\GatoGraphQLContainerConfigurationServiceTrait;

class GatoGraphQLArrowFnUnionTypeChainedRuleContainerConfigurationService extends AbstractPluginArrowFnUnionTypeChainedRuleContainerConfigurationService
{
    use GatoGraphQLContainerConfigurationServiceTrait;

    protected function getPaths(): array
    {
        return [
            $this->pluginDir . '/vendor/getpop/component-model/src/TypeResolvers/AbstractRelationalTypeResolver.php',
            $this->pluginDir . '/vendor/getpop/component-model/src/TypeResolvers/ObjectType/AbstractObjectTypeResolver.php',
            $this->pluginDir . '/vendor/getpop/engine/src/DirectiveResolvers/FilterIDsSatisfyingConditionFieldDirectiveResolverTrait.php',
            $this->pluginDir . '/vendor/pop-cms-schema/menus/src/RelationalTypeDataLoaders/ObjectType/MenuItemObjectTypeDataLoader.php',
            $this->pluginDir . '/vendor/pop-cms-schema/menus/src/RelationalTypeDataLoaders/ObjectType/MenuObjectTypeDataLoader.php',
            $this->pluginDir . '/vendor/pop-cms-schema/user-avatars/src/RelationalTypeDataLoaders/ObjectType/UserAvatarObjectTypeDataLoader.php',
            $this->pluginDir . '/vendor/pop-wp-schema/menus/src/FieldResolvers/ObjectType/MenuObjectTypeFieldResolver.php',
        ];
    }
}
