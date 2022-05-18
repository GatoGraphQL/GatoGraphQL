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

    public function filterHeadmoduleDataloadQueryArgs(array $component, array &$query, array $source = null): void
    {
        if ($activeDataloadQueryArgsFilteringModules = $this->getActiveDataloadQueryArgsFilteringComponents($component, $source)) {
            foreach ($activeDataloadQueryArgsFilteringModules as $subComponent) {
                /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
                $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($subComponent);
                $value = $dataloadQueryArgsFilterInputComponentProcessor->getValue($subComponent, $source);
                if ($filterInput = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInput($subComponent)) {
                    /** @var FilterInputProcessorInterface */
                    $filterInputProcessor = $this->getFilterInputProcessorManager()->getProcessor($filterInput);
                    $filterInputProcessor->filterDataloadQueryArgs($filterInput, $query, $value);
                }
            }
        }
    }

    public function getActiveDataloadQueryArgsFilteringComponents(array $component, array $source = null): array
    {
        // Search for cached result
        $cacheKey = json_encode($source ?? []);
        $this->activeDataloadQueryArgsFilteringModules[$cacheKey] = $this->activeDataloadQueryArgsFilteringModules[$cacheKey] ?? [];
        if (!is_null($this->activeDataloadQueryArgsFilteringModules[$cacheKey][$component[1]] ?? null)) {
            return $this->activeDataloadQueryArgsFilteringModules[$cacheKey][$component[1]];
        }

        $components = [];
        // Check if the component has any filtercomponent
        if ($dataloadQueryArgsFilteringModules = $this->getDataloadQueryArgsFilteringComponents($component)) {
            // Check if if we're currently filtering by any filtercomponent
            $components = array_filter(
                $dataloadQueryArgsFilteringModules,
                function (array $component) use ($source) {
                    /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
                    $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($component);
                    return $dataloadQueryArgsFilterInputComponentProcessor->isInputSetInSource($component, $source);
                }
            );
        }

        $this->activeDataloadQueryArgsFilteringModules[$cacheKey][$component[1]] = $components;
        return $components;
    }

    public function getDataloadQueryArgsFilteringComponents(array $component): array
    {
        return array_values(array_filter(
            $this->getDatasetmoduletreeSectionFlattenedComponents($component),
            function ($component) {
                return $this->getComponentProcessorManager()->getProcessor($component) instanceof DataloadQueryArgsFilterInputComponentProcessorInterface;
            }
        ));
    }
}
