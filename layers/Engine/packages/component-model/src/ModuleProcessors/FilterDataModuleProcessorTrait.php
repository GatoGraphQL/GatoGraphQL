<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait FilterDataModuleProcessorTrait
{
    abstract protected function getModuleProcessorManager(): ModuleProcessorManagerInterface;
    abstract protected function getFilterInputProcessorManager(): FilterInputProcessorManagerInterface;

    /**
     * @var array<string, array<string[]>>
     */
    protected array $activeDataloadQueryArgsFilteringModules = [];

    public function filterHeadmoduleDataloadQueryArgs(array $module, array &$query, array $source = null): void
    {
        if ($activeDataloadQueryArgsFilteringModules = $this->getActiveDataloadQueryArgsFilteringModules($module, $source)) {
            foreach ($activeDataloadQueryArgsFilteringModules as $submodule) {
                /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
                $dataloadQueryArgsFilterInputModuleProcessor = $this->getModuleProcessorManager()->getProcessor($submodule);
                $value = $dataloadQueryArgsFilterInputModuleProcessor->getValue($submodule, $source);
                if ($filterInput = $dataloadQueryArgsFilterInputModuleProcessor->getFilterInput($submodule)) {
                    /** @var FilterInputProcessorInterface */
                    $filterInputProcessor = $this->getFilterInputProcessorManager()->getProcessor($filterInput);
                    $filterInputProcessor->filterDataloadQueryArgs($filterInput, $query, $value);
                }
            }
        }
    }

    public function getActiveDataloadQueryArgsFilteringModules(array $module, array $source = null): array
    {
        // Search for cached result
        $cacheKey = json_encode($source ?? []);
        $this->activeDataloadQueryArgsFilteringModules[$cacheKey] = $this->activeDataloadQueryArgsFilteringModules[$cacheKey] ?? [];
        if (!is_null($this->activeDataloadQueryArgsFilteringModules[$cacheKey][$module[1]] ?? null)) {
            return $this->activeDataloadQueryArgsFilteringModules[$cacheKey][$module[1]];
        }

        $modules = [];
        // Check if the module has any filtercomponent
        if ($dataloadQueryArgsFilteringModules = $this->getDataloadQueryArgsFilteringModules($module)) {
            // Check if if we're currently filtering by any filtercomponent
            $modules = array_filter(
                $dataloadQueryArgsFilteringModules,
                function (array $module) use ($source) {
                    /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
                    $dataloadQueryArgsFilterInputModuleProcessor = $this->getModuleProcessorManager()->getProcessor($module);
                    return $dataloadQueryArgsFilterInputModuleProcessor->isInputSetInSource($module, $source);
                }
            );
        }

        $this->activeDataloadQueryArgsFilteringModules[$cacheKey][$module[1]] = $modules;
        return $modules;
    }

    public function getDataloadQueryArgsFilteringModules(array $module): array
    {
        return array_values(array_filter(
            $this->getDatasetmoduletreeSectionFlattenedModules($module),
            function ($module) {
                return $this->getModuleProcessorManager()->getProcessor($module) instanceof DataloadQueryArgsFilterInputModuleProcessorInterface;
            }
        ));
    }
}
