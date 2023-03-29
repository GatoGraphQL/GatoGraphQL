<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

class MonorepoArrowFnUnionTypeChainedRuleContainerConfigurationService extends AbstractArrowFnUnionTypeChainedRuleContainerConfigurationService
{
    protected function getPaths(): array
    {
        return [
            $this->rootDirectory . '/layers/Engine/packages/component-model/src/TypeResolvers/AbstractRelationalTypeResolver.php',
            $this->rootDirectory . '/layers/Engine/packages/component-model/src/TypeResolvers/ObjectType/AbstractObjectTypeResolver.php',
            $this->rootDirectory . '/layers/Engine/packages/engine/src/DirectiveResolvers/FilterIDsSatisfyingConditionFieldDirectiveResolverTrait.php',
            $this->rootDirectory . '/layers/CMSSchema/packages/menus/src/RelationalTypeDataLoaders/ObjectType/MenuItemObjectTypeDataLoader.php',
            $this->rootDirectory . '/layers/CMSSchema/packages/menus/src/RelationalTypeDataLoaders/ObjectType/MenuObjectTypeDataLoader.php',
            $this->rootDirectory . '/layers/CMSSchema/packages/user-avatars/src/RelationalTypeDataLoaders/ObjectType/UserAvatarObjectTypeDataLoader.php',
            $this->rootDirectory . '/layers/WPSchema/packages/menus/src/FieldResolvers/ObjectType/MenuObjectTypeFieldResolver.php',
        ];
    }
}
