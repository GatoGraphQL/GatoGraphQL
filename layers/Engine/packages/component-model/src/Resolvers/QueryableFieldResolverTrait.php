<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\ComponentProcessors\FilterInputContainerComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;

trait QueryableFieldResolverTrait
{
    abstract protected function getComponentProcessorManager(): ComponentProcessorManagerInterface;

    protected function getFilterFieldArgNameTypeResolvers(array $filterDataloadingModule): array
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataComponentProcessor->getFieldFilterInputNameTypeResolvers($filterDataloadingModule);
    }

    protected function getFilterFieldArgDescription(array $filterDataloadingModule, string $fieldArgName): ?string
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataComponentProcessor->getFieldFilterInputDescription($filterDataloadingModule, $fieldArgName);
    }

    protected function getFilterFieldArgDefaultValue(array $filterDataloadingModule, string $fieldArgName): mixed
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataComponentProcessor->getFieldFilterInputDefaultValue($filterDataloadingModule, $fieldArgName);
    }

    protected function getFilterFieldArgTypeModifiers(array $filterDataloadingModule, string $fieldArgName): int
    {
        /** @var FilterInputContainerComponentProcessorInterface */
        $filterDataComponentProcessor = $this->getComponentProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataComponentProcessor->getFieldFilterInputTypeModifiers($filterDataloadingModule, $fieldArgName);
    }
}
