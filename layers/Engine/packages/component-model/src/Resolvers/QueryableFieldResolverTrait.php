<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\ModuleProcessors\FilterInputContainerModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait QueryableFieldResolverTrait
{
    protected ModuleProcessorManagerInterface $moduleProcessorManager;

    #[Required]
    public function autowireQueryableFieldResolverTrait(
        ModuleProcessorManagerInterface $moduleProcessorManager,
    ): void {
        $this->moduleProcessorManager = $moduleProcessorManager;
    }

    protected function getFilterFieldArgNameTypeResolvers(array $filterDataloadingModule): array
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        return $filterDataModuleProcessor->getFieldFilterInputNameTypeResolvers($filterDataloadingModule);
    }

    protected function getFilterFieldArgDescription(array $filterDataloadingModule, string $fieldArgName): ?string
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        return $filterDataModuleProcessor->getFieldFilterInputDescription($filterDataloadingModule, $fieldArgName);
    }

    protected function getFilterFieldArgDeprecationDescription(array $filterDataloadingModule, string $fieldArgName): ?string
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        return $filterDataModuleProcessor->getFieldFilterInputDeprecationDescription($filterDataloadingModule, $fieldArgName);
    }

    protected function getFilterFieldArgDefaultValue(array $filterDataloadingModule, string $fieldArgName): mixed
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        return $filterDataModuleProcessor->getFieldFilterInputDefaultValue($filterDataloadingModule, $fieldArgName);
    }

    protected function getFilterFieldArgTypeModifiers(array $filterDataloadingModule, string $fieldArgName): int
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        return $filterDataModuleProcessor->getFieldFilterInputTypeModifiers($filterDataloadingModule, $fieldArgName);
    }
}
