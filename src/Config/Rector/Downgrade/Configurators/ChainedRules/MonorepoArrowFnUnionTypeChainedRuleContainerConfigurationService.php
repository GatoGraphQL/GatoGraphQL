<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

class MonorepoArrowFnUnionTypeChainedRuleContainerConfigurationService extends AbstractArrowFnUnionTypeChainedRuleContainerConfigurationService
{
    protected function getPaths(): array
    {
        return [
            $this->rootDirectory . '/layers/Engine/packages/component-model/src/TypeResolvers/AbstractObjectTypeResolver.php',
            $this->rootDirectory . '/layers/Engine/packages/engine/src/DirectiveResolvers/FilterIDsSatisfyingConditionDirectiveResolverTrait.php',
            $this->rootDirectory . '/layers/Schema/packages/menus/src/TypeDataLoaders/MenuItemTypeDataLoader.php',
            $this->rootDirectory . '/layers/Schema/packages/menus/src/TypeDataLoaders/MenuTypeDataLoader.php',
            $this->rootDirectory . '/layers/Schema/packages/user-avatars/src/TypeDataLoaders/UserAvatarTypeDataLoader.php',
            $this->rootDirectory . '/layers/WPSchema/packages/menus/src/FieldResolvers/MenuFieldResolver.php',
        ];
    }
}
