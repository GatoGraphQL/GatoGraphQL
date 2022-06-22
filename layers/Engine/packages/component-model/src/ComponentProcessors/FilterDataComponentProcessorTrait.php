<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;

trait FilterDataComponentProcessorTrait
{
    abstract protected function getComponentProcessorManager(): ComponentProcessorManagerInterface;

    /**
     * @var array<string,array<string,Component[]>>
     */
    protected array $activeDataloadQueryArgsFilteringComponents = [];

    public function filterHeadcomponentDataloadQueryArgs(Component $component, array &$query, array $source = null): void
    {
        if ($activeDataloadQueryArgsFilteringComponents = $this->getActiveDataloadQueryArgsFilteringComponents($component, $source)) {
            foreach ($activeDataloadQueryArgsFilteringComponents as $subcomponent) {
                /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
                $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($subcomponent);
                $value = $dataloadQueryArgsFilterInputComponentProcessor->getValue($subcomponent, $source);
                if ($filterInput = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInput($subcomponent)) {
                    $filterInput->filterDataloadQueryArgs($query, $value);
                }
            }
        }
    }

    /**
     * @return Component[]
     */
    public function getActiveDataloadQueryArgsFilteringComponents(Component $component, array $source = null): array
    {
        // Search for cached result
        $cacheKey = json_encode($source ?? []);
        $this->activeDataloadQueryArgsFilteringComponents[$cacheKey] ??= [];
        if (isset($this->activeDataloadQueryArgsFilteringComponents[$cacheKey][$component->name])) {
            return $this->activeDataloadQueryArgsFilteringComponents[$cacheKey][$component->name];
        }

        $components = [];
        // Check if the component has any filtercomponent
        if ($dataloadQueryArgsFilteringComponents = $this->getDataloadQueryArgsFilteringComponents($component)) {
            // Check if if we're currently filtering by any filtercomponent
            $components = array_filter(
                $dataloadQueryArgsFilteringComponents,
                function (Component $component) use ($source) {
                    /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
                    $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($component);
                    return $dataloadQueryArgsFilterInputComponentProcessor->isInputSetInSource($component, $source);
                }
            );
        }

        $this->activeDataloadQueryArgsFilteringComponents[$cacheKey][$component->name] = $components;
        return $components;
    }

    /**
     * @return Component[]
     */
    public function getDataloadQueryArgsFilteringComponents(Component $component): array
    {
        return array_values(array_filter(
            $this->getDatasetcomponentTreeSectionFlattenedComponents($component),
            fn (Component $component) => $this->getComponentProcessorManager()->getComponentProcessor($component) instanceof DataloadQueryArgsFilterInputComponentProcessorInterface
        ));
    }
}
