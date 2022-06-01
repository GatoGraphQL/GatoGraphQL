<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

trait FilterDataComponentProcessorTrait
{
    abstract protected function getComponentProcessorManager(): ComponentProcessorManagerInterface;

    /**
     * @var array<string, array<string[]>>
     */
    protected array $activeDataloadQueryArgsFilteringComponents = [];

    public function filterHeadcomponentDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$query, array $source = null): void
    {
        if ($activeDataloadQueryArgsFilteringComponents = $this->getActiveDataloadQueryArgsFilteringComponents($component, $source)) {
            foreach ($activeDataloadQueryArgsFilteringComponents as $subComponent) {
                /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
                $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($subComponent);
                $value = $dataloadQueryArgsFilterInputComponentProcessor->getValue($subComponent, $source);
                if ($filterInput = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInput($subComponent)) {
                    $filterInput->filterDataloadQueryArgs($query, $value);
                }
            }
        }
    }

    public function getActiveDataloadQueryArgsFilteringComponents(\PoP\ComponentModel\Component\Component $component, array $source = null): array
    {
        // Search for cached result
        $cacheKey = json_encode($source ?? []);
        $this->activeDataloadQueryArgsFilteringComponents[$cacheKey] = $this->activeDataloadQueryArgsFilteringComponents[$cacheKey] ?? [];
        if (!is_null($this->activeDataloadQueryArgsFilteringComponents[$cacheKey][$component[1]] ?? null)) {
            return $this->activeDataloadQueryArgsFilteringComponents[$cacheKey][$component[1]];
        }

        $components = [];
        // Check if the component has any filtercomponent
        if ($dataloadQueryArgsFilteringComponents = $this->getDataloadQueryArgsFilteringComponents($component)) {
            // Check if if we're currently filtering by any filtercomponent
            $components = array_filter(
                $dataloadQueryArgsFilteringComponents,
                function (\PoP\ComponentModel\Component\Component $component) use ($source) {
                    /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
                    $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($component);
                    return $dataloadQueryArgsFilterInputComponentProcessor->isInputSetInSource($component, $source);
                }
            );
        }

        $this->activeDataloadQueryArgsFilteringComponents[$cacheKey][$component[1]] = $components;
        return $components;
    }

    public function getDataloadQueryArgsFilteringComponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array_values(array_filter(
            $this->getDatasetcomponentTreeSectionFlattenedComponents($component),
            fn (\PoP\ComponentModel\Component\Component $component) => $this->getComponentProcessorManager()->getProcessor($component) instanceof DataloadQueryArgsFilterInputComponentProcessorInterface
        ));
    }
}
