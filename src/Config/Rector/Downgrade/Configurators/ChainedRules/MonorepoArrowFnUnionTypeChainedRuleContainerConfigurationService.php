<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

class MonorepoArrowFnUnionTypeChainedRuleContainerConfigurationService extends AbstractArrowFnUnionTypeChainedRuleContainerConfigurationService
{
    protected function getPaths(): array
    {
        return [
            $this->rootDirectory . '/layers/Engine/packages/component-model/src/TypeResolvers/AbstractRelationalTypeResolver.php',
            $this->rootDirectory . '/layers/Engine/packages/component-model/src/TypeResolvers/Object/AbstractObjectTypeResolver.php',
            $this->rootDirectory . '/layers/Engine/packages/engine/src/DirectiveResolvers/FilterIDsSatisfyingConditionDirectiveResolverTrait.php',
            $this->rootDirectory . '/layers/Schema/packages/menus/src/RelationalTypeDataLoaders/Object/MenuItemTypeDataLoader.php',
            $this->rootDirectory . '/layers/Schema/packages/menus/src/RelationalTypeDataLoaders/Object/MenuTypeDataLoader.php',
            $this->rootDirectory . '/layers/Schema/packages/user-avatars/src/RelationalTypeDataLoaders/Object/UserAvatarTypeDataLoader.php',
            $this->rootDirectory . '/layers/WPSchema/packages/menus/src/FieldResolvers/MenuFieldResolver.php',
        ];
    }
}
