<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\ModuleProcessors\FilterInputContainerModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait QueryableFieldResolverTrait
{
    private ?ModuleProcessorManagerInterface $moduleProcessorManager = null;

    public function setModuleProcessorManager(ModuleProcessorManagerInterface $moduleProcessorManager): void
    {
        $this->moduleProcessorManager = $moduleProcessorManager;
    }
    protected function getModuleProcessorManager(): ModuleProcessorManagerInterface
    {
        return $this->moduleProcessorManager ??= $this->instanceManager->getInstance(ModuleProcessorManagerInterface::class);
    }

    protected function getFilterFieldArgNameTypeResolvers(array $filterDataloadingModule): array
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->getModuleProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataModuleProcessor->getFieldFilterInputNameTypeResolvers($filterDataloadingModule);
    }

    protected function getFilterFieldArgDescription(array $filterDataloadingModule, string $fieldArgName): ?string
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->getModuleProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataModuleProcessor->getFieldFilterInputDescription($filterDataloadingModule, $fieldArgName);
    }

    protected function getFilterFieldArgDefaultValue(array $filterDataloadingModule, string $fieldArgName): mixed
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->getModuleProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataModuleProcessor->getFieldFilterInputDefaultValue($filterDataloadingModule, $fieldArgName);
    }

    protected function getFilterFieldArgTypeModifiers(array $filterDataloadingModule, string $fieldArgName): int
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->getModuleProcessorManager()->getProcessor($filterDataloadingModule);
        return $filterDataModuleProcessor->getFieldFilterInputTypeModifiers($filterDataloadingModule, $fieldArgName);
    }
}
