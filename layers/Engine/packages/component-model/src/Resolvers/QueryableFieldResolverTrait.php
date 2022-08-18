<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\ComponentProcessors\FilterInputContainerComponentProcessorInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

trait QueryableFieldResolverTrait
{
    abstract protected function getComponentProcessorManager(): ComponentProcessorManagerInterface;

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    protected function getFilterFieldArgNameTypeResolvers(Component $filterDataloadingComponent): array
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
        return $filterDataComponentProcessor->getFieldFilterInputNameTypeResolvers($filterDataloadingComponent);
    }

    protected function getFilterFieldArgDescription(Component $filterDataloadingComponent, string $fieldArgName): ?string
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
        return $filterDataComponentProcessor->getFieldFilterInputDescription($filterDataloadingComponent, $fieldArgName);
    }

    protected function getFilterFieldArgDefaultValue(Component $filterDataloadingComponent, string $fieldArgName): mixed
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
        return $filterDataComponentProcessor->getFieldFilterInputDefaultValue($filterDataloadingComponent, $fieldArgName);
    }

    protected function getFilterFieldArgTypeModifiers(Component $filterDataloadingComponent, string $fieldArgName): int
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
        return $filterDataComponentProcessor->getFieldFilterInputTypeModifiers($filterDataloadingComponent, $fieldArgName);
    }
}
