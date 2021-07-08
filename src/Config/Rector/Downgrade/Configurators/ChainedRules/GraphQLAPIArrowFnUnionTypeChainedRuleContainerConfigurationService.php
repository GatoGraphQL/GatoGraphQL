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
            $this->pluginDir . '/vendor/getpop/component-model/src/TypeResolvers/AbstractTypeResolver.php',
            $this->pluginDir . '/vendor/getpop/engine/src/DirectiveResolvers/FilterIDsSatisfyingConditionDirectiveResolverTrait.php',
            $this->pluginDir . '/vendor/pop-schema/menus/src/TypeDataLoaders/MenuItemTypeDataLoader.php',
            $this->pluginDir . '/vendor/pop-schema/menus/src/TypeDataLoaders/MenuTypeDataLoader.php',
        ];
    }
}
