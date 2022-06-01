<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\FilterInputContainerComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;

trait QueryableFieldResolverTrait
{
    abstract protected function getComponentProcessorManager(): ComponentProcessorManagerInterface;

    protected function getFilterFieldArgNameTypeResolvers(Component $filterDataloadingModule): array
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataComponentProcessor->getFieldFilterInputNameTypeResolvers($filterDataloadingModule);
    }

    protected function getFilterFieldArgDescription(Component $filterDataloadingModule, string $fieldArgName): ?string
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataComponentProcessor->getFieldFilterInputDescription($filterDataloadingModule, $fieldArgName);
    }

    protected function getFilterFieldArgDefaultValue(Component $filterDataloadingModule, string $fieldArgName): mixed
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataComponentProcessor->getFieldFilterInputDefaultValue($filterDataloadingModule, $fieldArgName);
    }

    protected function getFilterFieldArgTypeModifiers(Component $filterDataloadingModule, string $fieldArgName): int
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataComponentProcessor->getFieldFilterInputTypeModifiers($filterDataloadingModule, $fieldArgName);
    }
}
