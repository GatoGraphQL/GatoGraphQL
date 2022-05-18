<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentFilters;

use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\ModulePath\ModulePathManagerInterface;

class ModulePaths extends AbstractComponentFilter
{
    private ?ModulePathHelpersInterface $modulePathHelpers = null;

    final public function setModulePathHelpers(ModulePathHelpersInterface $modulePathHelpers): void
    {
        $this->modulePathHelpers = $modulePathHelpers;
    }
    final protected function getModulePathHelpers(): ModulePathHelpersInterface
    {
        return $this->modulePathHelpers ??= $this->instanceManager->getInstance(ModulePathHelpersInterface::class);
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

    private ?ModulePathManagerInterface $modulePathManager = null;

    final public function setModulePathManager(ModulePathManagerInterface $modulePathManager): void
    {
        $this->modulePathManager = $modulePathManager;
    }
    final protected function getModulePathManager(): ModulePathManagerInterface
    {
        return $this->modulePathManager ??= $this->instanceManager->getInstance(ModulePathManagerInterface::class);
    }

    protected function init(): void
    {
        $this->paths = $this->getModulePathHelpers()->getModulePaths();
        $this->propagation_unsettled_paths = $this->paths;
        $this->backlog_unsettled_paths = array();
    }

    public function getName(): string
    {
        return 'componentVariationPaths';
    }

    public function excludeModule(array $componentVariation, array &$props): bool
    {
        if (is_null($this->paths)) {
            $this->init();
        }

        // If there are no paths to include, then exclude everything
        if (!$this->paths) {
            return true;
        }

        // The module is included for rendering, if either there is no path, or if there is, if it's the last module
        // on the path or any module thereafter
        if (!$this->propagation_unsettled_paths) {
            return false;
        }

        // Check if this module is the last item of any componentVariationPath
        foreach ($this->propagation_unsettled_paths as $unsettled_path) {
            if (count($unsettled_path) == 1 && $unsettled_path[0] == $componentVariation) {
                return false;
            }
        }

        return true;
    }

    public function removeExcludedSubmodules(array $componentVariation, array $subComponentVariations): array
    {
        if (is_null($this->paths)) {
            $this->init();
        }

        // If there are no remaining path left, then everything goes in
        if (!$this->propagation_unsettled_paths) {
            return $subComponentVariations;
        }

        // $componentVariation_unsettled_path: Start only from the specified module. It is passed under URL param "componentVariationPaths", and it's the list of module paths
        // starting from the entry, and joined by ".", like this: componentVariationPaths[]=toplevel.pagesection-top.frame-top.block-notifications-scroll-list
        // This way, the component can interact with itself to fetch or post data, etc
        $matching_subComponentVariations = array();
        foreach ($this->propagation_unsettled_paths as $unsettled_path) {
            // Validate that the current module is at the head of the path
            // This validation will work for the entry module only, since the array_intersect below will guarantee that only the path modules are returned
            $unsettled_path_componentVariation = $unsettled_path[0];
            if (count($unsettled_path) == 1) {
                // We reached the end of the unsettled path => from now on, all modules must be included
                if ($unsettled_path_componentVariation == $componentVariation) {
                    return $subComponentVariations;
                }
            } else {
                // Then, check that the following element in the unsettled_path, which is the subComponentVariation, is on the subComponentVariations
                $unsettled_path_subComponentVariation = $unsettled_path[1];
                if ($unsettled_path_componentVariation == $componentVariation && in_array($unsettled_path_subComponentVariation, $subComponentVariations) && !in_array($unsettled_path_subComponentVariation, $matching_subComponentVariations)) {
                    $matching_subComponentVariations[] = $unsettled_path_subComponentVariation;
                }
            }
        }

        return $matching_subComponentVariations;
    }

    /**
     * The `prepare` function advances the componentVariationPath one level down, when interating into the subComponentVariations, and then calling `restore` the value goes one level up again
     */
    public function prepareForPropagation(array $componentVariation, array &$props): void
    {
        if (is_null($this->paths)) {
            $this->init();
        }
        if ($this->paths) {
            // Save the current propagation_unsettled_paths, to restore it later on
            $this->backlog_unsettled_paths[$this->getBacklogEntry()] = $this->propagation_unsettled_paths;

            $matching_unsettled_paths = array();
            foreach ($this->propagation_unsettled_paths as $unsettled_path) {
                $componentVariation_unsettled_path = $unsettled_path[0];
                if ($componentVariation_unsettled_path == $componentVariation) {
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
    public function restoreFromPropagation(array $componentVariation, array &$props): void
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
