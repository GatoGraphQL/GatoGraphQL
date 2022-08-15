<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

class MonorepoArrowFnMixedTypeChainedRuleContainerConfigurationService extends AbstractArrowFnMixedTypeChainedRuleContainerConfigurationService
{
    protected function getPaths(): array
    {
        return [
            $this->rootDirectory . '/layers/Engine/packages/component-model/src/Resolvers/FieldOrDirectiveResolverTrait.php',
            $this->rootDirectory . '/layers/Engine/packages/component-model/src/TypeResolvers/AbstractRelationalTypeResolver.php',
            $this->rootDirectory . '/layers/Engine/packages/component-model/src/TypeResolvers/ObjectType/AbstractObjectTypeResolver.php',
        ];
    }
}
