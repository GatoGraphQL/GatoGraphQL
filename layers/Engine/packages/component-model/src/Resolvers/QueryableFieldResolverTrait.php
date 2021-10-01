<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
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

    protected function getFilterSchemaFieldArgNameResolvers(array $filterDataloadingModule): array
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        $filterQueryArgsModules = $filterDataModuleProcessor->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
        $schemaFieldArgNameResolvers = [];
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
            $dataloadQueryArgsFilterInputModuleProcessor = $this->moduleProcessorManager->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputModuleProcessor->getName($module);
            $schemaFieldArgNameResolvers[$filterInputName] = $dataloadQueryArgsFilterInputModuleProcessor->getSchemaFilterInputTypeResolver($module);
        }
        return $schemaFieldArgNameResolvers;
    }

    protected function getFilterSchemaFieldArgDescription(array $filterDataloadingModule, string $fieldArgName): ?string
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        $filterQueryArgsModules = $filterDataModuleProcessor->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
            $dataloadQueryArgsFilterInputModuleProcessor = $this->moduleProcessorManager->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputModuleProcessor->getName($module);
            if ($filterInputName !== $fieldArgName) {
                continue;
            }
            return $dataloadQueryArgsFilterInputModuleProcessor->getSchemaFilterInputDescription($module);
        }
        return null;
    }

    protected function getFilterSchemaFieldArgDefaultValue(array $filterDataloadingModule, string $fieldArgName): mixed
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        $filterQueryArgsModules = $filterDataModuleProcessor->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
            $dataloadQueryArgsFilterInputModuleProcessor = $this->moduleProcessorManager->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputModuleProcessor->getName($module);
            if ($filterInputName !== $fieldArgName) {
                continue;
            }
            return $dataloadQueryArgsFilterInputModuleProcessor->getSchemaFilterInputDefaultValue($module);
        }
        return null;
    }

    protected function getFilterSchemaFieldArgTypeModifiers(array $filterDataloadingModule, string $fieldArgName): ?int
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        $filterQueryArgsModules = $filterDataModuleProcessor->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
            $dataloadQueryArgsFilterInputModuleProcessor = $this->moduleProcessorManager->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputModuleProcessor->getName($module);
            if ($filterInputName !== $fieldArgName) {
                continue;
            }
            return $dataloadQueryArgsFilterInputModuleProcessor->getSchemaFilterInputTypeModifiers($module);
        }
        return null;
    }
}
