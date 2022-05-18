<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorManagerInterface;

trait FilterDataComponentProcessorTrait
{
    abstract protected function getComponentProcessorManager(): ComponentProcessorManagerInterface;
    abstract protected function getFilterInputProcessorManager(): FilterInputProcessorManagerInterface;

    /**
     * @var array<string, array<string[]>>
     */
    protected array $activeDataloadQueryArgsFilteringModules = [];

    public function filterHeadmoduleDataloadQueryArgs(array $componentVariation, array &$query, array $source = null): void
    {
        if ($activeDataloadQueryArgsFilteringModules = $this->getActiveDataloadQueryArgsFilteringComponentVariations($componentVariation, $source)) {
            foreach ($activeDataloadQueryArgsFilteringModules as $submodule) {
                /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
                $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($submodule);
                $value = $dataloadQueryArgsFilterInputComponentProcessor->getValue($submodule, $source);
                if ($filterInput = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInput($submodule)) {
                    /** @var FilterInputProcessorInterface */
                    $filterInputProcessor = $this->getFilterInputProcessorManager()->getProcessor($filterInput);
                    $filterInputProcessor->filterDataloadQueryArgs($filterInput, $query, $value);
                }
            }
        }
    }

    public function getActiveDataloadQueryArgsFilteringComponentVariations(array $componentVariation, array $source = null): array
    {
        // Search for cached result
        $cacheKey = json_encode($source ?? []);
        $this->activeDataloadQueryArgsFilteringModules[$cacheKey] = $this->activeDataloadQueryArgsFilteringModules[$cacheKey] ?? [];
        if (!is_null($this->activeDataloadQueryArgsFilteringModules[$cacheKey][$componentVariation[1]] ?? null)) {
            return $this->activeDataloadQueryArgsFilteringModules[$cacheKey][$componentVariation[1]];
        }

        $componentVariations = [];
        // Check if the component variation has any filtercomponent
        if ($dataloadQueryArgsFilteringModules = $this->getDataloadQueryArgsFilteringComponentVariations($componentVariation)) {
            // Check if if we're currently filtering by any filtercomponent
            $componentVariations = array_filter(
                $dataloadQueryArgsFilteringModules,
                function (array $componentVariation) use ($source) {
                    /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
                    $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($componentVariation);
                    return $dataloadQueryArgsFilterInputComponentProcessor->isInputSetInSource($componentVariation, $source);
                }
            );
        }

        $this->activeDataloadQueryArgsFilteringModules[$cacheKey][$componentVariation[1]] = $componentVariations;
        return $componentVariations;
    }

    public function getDataloadQueryArgsFilteringComponentVariations(array $componentVariation): array
    {
        return array_values(array_filter(
            $this->getDatasetmoduletreeSectionFlattenedComponentVariations($componentVariation),
            function ($componentVariation) {
                return $this->getComponentProcessorManager()->getProcessor($componentVariation) instanceof DataloadQueryArgsFilterInputComponentProcessorInterface;
            }
        ));
    }
}
