<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentFilters;

use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\ModulePath\ModulePathManagerInterface;

class ModulePaths extends AbstractComponentFilter
{
    private ?ModulePathHelpersInterface $componentPathHelpers = null;

    final public function setModulePathHelpers(ModulePathHelpersInterface $componentPathHelpers): void
    {
        $this->componentPathHelpers = $componentPathHelpers;
    }
    final protected function getModulePathHelpers(): ModulePathHelpersInterface
    {
        return $this->componentPathHelpers ??= $this->instanceManager->getInstance(ModulePathHelpersInterface::class);
    }

    /**
     * @var array[]
     */
    protected ?array $paths = null;
    /**
     * @var array[]
     */
    protected array $propagation_unsettled_paths = [];
    /**
     * @var array<string, array>
     */
    protected array $backlog_unsettled_paths = [];

    private ?ModulePathManagerInterface $componentPathManager = null;

    final public function setModulePathManager(ModulePathManagerInterface $componentPathManager): void
    {
        $this->componentPathManager = $componentPathManager;
    }
    final protected function getModulePathManager(): ModulePathManagerInterface
    {
        return $this->componentPathManager ??= $this->instanceManager->getInstance(ModulePathManagerInterface::class);
    }

    protected function init(): void
    {
        $this->paths = $this->getModulePathHelpers()->getModulePaths();
        $this->propagation_unsettled_paths = $this->paths;
        $this->backlog_unsettled_paths = array();
    }

    public function getName(): string
    {
        return 'componentPaths';
    }

    public function excludeModule(array $component, array &$props): bool
    {
        if (is_null($this->paths)) {
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
            if (count($unsettled_path) == 1 && $unsettled_path[0] == $component) {
                return false;
            }
        }

        return true;
    }

    public function removeExcludedSubmodules(array $component, array $subComponents): array
    {
        if (is_null($this->paths)) {
            $this->init();
        }

        // If there are no remaining path left, then everything goes in
        if (!$this->propagation_unsettled_paths) {
            return $subComponents;
        }

        // $component_unsettled_path: Start only from the specified component. It is passed under URL param "componentPaths", and it's the list of component paths
        // starting from the entry, and joined by ".", like this: componentPaths[]=toplevel.pagesection-top.frame-top.block-notifications-scroll-list
        // This way, the component can interact with itself to fetch or post data, etc
        $matching_subComponents = array();
        foreach ($this->propagation_unsettled_paths as $unsettled_path) {
            // Validate that the current component is at the head of the path
            // This validation will work for the entry component only, since the array_intersect below will guarantee that only the path components are returned
            $unsettled_path_component = $unsettled_path[0];
            if (count($unsettled_path) == 1) {
                // We reached the end of the unsettled path => from now on, all components must be included
                if ($unsettled_path_component == $component) {
                    return $subComponents;
                }
            } else {
                // Then, check that the following element in the unsettled_path, which is the subComponent, is on the subComponents
                $unsettled_path_subComponent = $unsettled_path[1];
                if ($unsettled_path_component == $component && in_array($unsettled_path_subComponent, $subComponents) && !in_array($unsettled_path_subComponent, $matching_subComponents)) {
                    $matching_subComponents[] = $unsettled_path_subComponent;
                }
            }
        }

        return $matching_subComponents;
    }

    /**
     * The `prepare` function advances the componentPath one level down, when interating into the subComponents, and then calling `restore` the value goes one level up again
     */
    public function prepareForPropagation(array $component, array &$props): void
    {
        if (is_null($this->paths)) {
            $this->init();
        }
        if ($this->paths) {
            // Save the current propagation_unsettled_paths, to restore it later on
            $this->backlog_unsettled_paths[$this->getBacklogEntry()] = $this->propagation_unsettled_paths;

            $matching_unsettled_paths = array();
            foreach ($this->propagation_unsettled_paths as $unsettled_path) {
                $component_unsettled_path = $unsettled_path[0];
                if ($component_unsettled_path == $component) {
                    array_shift($unsettled_path);
                    // If there are still elements, then add it to the list
                    if ($unsettled_path) {
                        $matching_unsettled_paths[] = $unsettled_path;
                    }
                }
            }
            $this->propagation_unsettled_paths = $matching_unsettled_paths;
        }
    }
    public function restoreFromPropagation(array $component, array &$props): void
    {
        if (is_null($this->paths)) {
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
        $entry = json_encode($this->getModulePathManager()->getPropagationCurrentPath());
        if ($entry === false) {
            return '';
        }
        return $entry;
    }
}
