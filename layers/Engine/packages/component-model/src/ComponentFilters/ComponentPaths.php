<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentFilters;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface;
use PoP\ComponentModel\ComponentPath\ComponentPathManagerInterface;

class ComponentPaths extends AbstractComponentFilter
{
    private ?ComponentPathHelpersInterface $componentPathHelpers = null;

    final protected function getComponentPathHelpers(): ComponentPathHelpersInterface
    {
        if ($this->componentPathHelpers === null) {
            /** @var ComponentPathHelpersInterface */
            $componentPathHelpers = $this->instanceManager->getInstance(ComponentPathHelpersInterface::class);
            $this->componentPathHelpers = $componentPathHelpers;
        }
        return $this->componentPathHelpers;
    }

    /**
     * @var array<array<Component|null>>|null
     */
    protected ?array $paths = null;
    /**
     * @var array<array<Component|null>>
     */
    protected array $propagation_unsettled_paths = [];
    /**
     * @var array<string,array<array<Component|null>>>
     */
    protected array $backlog_unsettled_paths = [];

    private ?ComponentPathManagerInterface $componentPathManager = null;

    final protected function getComponentPathManager(): ComponentPathManagerInterface
    {
        if ($this->componentPathManager === null) {
            /** @var ComponentPathManagerInterface */
            $componentPathManager = $this->instanceManager->getInstance(ComponentPathManagerInterface::class);
            $this->componentPathManager = $componentPathManager;
        }
        return $this->componentPathManager;
    }

    protected function init(): void
    {
        $this->paths = $this->getComponentPathHelpers()->getComponentPaths();
        $this->propagation_unsettled_paths = $this->paths;
        $this->backlog_unsettled_paths = array();
    }

    public function getName(): string
    {
        return 'componentPaths';
    }

    /**
     * @param array<string,mixed> $props
     */
    public function excludeSubcomponent(Component $component, array &$props): bool
    {
        if ($this->paths === null) {
            $this->init();
        }

        // If there are no paths to include, then exclude everything
        if (!$this->paths) {
            return true;
        }

        // The component is included for rendering, if either there is no path, or if there is, if it's the last component
        // on the path or any component thereafter
        if (!$this->propagation_unsettled_paths) {
            return false;
        }

        // Check if this component is the last item of any componentPath
        foreach ($this->propagation_unsettled_paths as $unsettled_path) {
            if (count($unsettled_path) === 1 && $unsettled_path[0] === $component) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Component[] $subcomponents
     * @return Component[]|mixed[]
     */
    public function removeExcludedSubcomponents(Component $component, array $subcomponents): array
    {
        if ($this->paths === null) {
            $this->init();
        }

        // If there are no remaining path left, then everything goes in
        if (!$this->propagation_unsettled_paths) {
            return $subcomponents;
        }

        // $component_unsettled_path: Start only from the specified component. It is passed under URL param "componentPaths", and it's the list of component paths
        // starting from the entry, and joined by ".", like this: componentPaths[]=toplevel.pagesection-top.frame-top.block-notifications-scroll-list
        // This way, the component can interact with itself to fetch or post data, etc
        $matching_subcomponents = array();
        foreach ($this->propagation_unsettled_paths as $unsettled_path) {
            // Validate that the current component is at the head of the path
            // This validation will work for the entry component only, since the array_intersect below will guarantee that only the path components are returned
            $unsettled_path_component = $unsettled_path[0];
            if (count($unsettled_path) === 1) {
                // We reached the end of the unsettled path => from now on, all components must be included
                if ($unsettled_path_component === $component) {
                    return $subcomponents;
                }
            } else {
                // Then, check that the following element in the unsettled_path, which is the subcomponent, is on the subcomponents
                $unsettled_path_subcomponent = $unsettled_path[1];
                if ($unsettled_path_component === $component && in_array($unsettled_path_subcomponent, $subcomponents) && !in_array($unsettled_path_subcomponent, $matching_subcomponents)) {
                    $matching_subcomponents[] = $unsettled_path_subcomponent;
                }
            }
        }

        return $matching_subcomponents;
    }

    /**
     * The `prepare` function advances the componentPath one level down, when iterating into the subcomponents, and then calling `restore` the value goes one level up again
     * @param array<string,mixed> $props
     */
    public function prepareForPropagation(Component $component, array &$props): void
    {
        if ($this->paths === null) {
            $this->init();
        }

        if (!$this->paths) {
            return;
        }

        // Save the current propagation_unsettled_paths, to restore it later on
        $this->backlog_unsettled_paths[$this->getBacklogEntry()] = $this->propagation_unsettled_paths;

        $matching_unsettled_paths = array();
        foreach ($this->propagation_unsettled_paths as $unsettled_path) {
            $component_unsettled_path = $unsettled_path[0];
            if ($component_unsettled_path === $component) {
                array_shift($unsettled_path);
                // If there are still elements, then add it to the list
                if (!$unsettled_path) {
                    continue;
                }
                $matching_unsettled_paths[] = $unsettled_path;
            }
        }
        $this->propagation_unsettled_paths = $matching_unsettled_paths;
    }
    /**
     * @param array<string,mixed> $props
     */
    public function restoreFromPropagation(Component $component, array &$props): void
    {
        if ($this->paths === null) {
            $this->init();
        }

        // Restore the previous propagation_unsettled_paths
        if ($this->paths) {
            $backlog_entry = $this->getBacklogEntry();
            // If the backlog is NULL and doing Extra URIs, set the propagation to $this->paths instead of NULL so it doesn't fail for the new round of generateAndProcessData
            $this->propagation_unsettled_paths = $this->backlog_unsettled_paths[$backlog_entry] ?? $this->paths;
            unset($this->backlog_unsettled_paths[$backlog_entry]);
        }
    }
    protected function getBacklogEntry(): string
    {
        $entry = json_encode($this->getComponentPathManager()->getPropagationCurrentPath());
        if ($entry === false) {
            return '';
        }
        return $entry;
    }
}
