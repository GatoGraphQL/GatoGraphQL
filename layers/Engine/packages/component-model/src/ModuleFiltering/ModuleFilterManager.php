<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleFiltering;

use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\ModuleFilters\ModuleFilterInterface;
use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\ModulePath\ModulePathManagerInterface;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootComponentConfiguration;
use PoP\Root\Services\BasicServiceTrait;

class ModuleFilterManager implements ModuleFilterManagerInterface
{
    use BasicServiceTrait;

    protected ?string $selected_filter_name = null;
    private ?ModuleFilterInterface $selected_filter = null;
    /**
     * @var array<string, ModuleFilterInterface>
     */
    protected array $modulefilters = [];
    protected bool $initialized = false;
    /**
     * From the moment in which a module is not excluded, every module from then on must also be included
     */
    protected ?string $not_excluded_ancestor_module = null;
    /**
     * @var array<array>|null
     */
    protected ?array $not_excluded_module_sets = null;
    /**
     * @var string[]|null
     */
    protected ?array $not_excluded_module_sets_as_string;
    /**
     * When targeting modules in pop-engine.php (eg: when doing ->get_dbobjectids())
     * those modules are already and always included, so no need to check
     * for their ancestors or anything
     */
    protected bool $neverExclude = false;

    private ?ModulePathManagerInterface $modulePathManager = null;
    private ?ModulePathHelpersInterface $modulePathHelpers = null;

    final public function setModulePathManager(ModulePathManagerInterface $modulePathManager): void
    {
        $this->modulePathManager = $modulePathManager;
    }
    final protected function getModulePathManager(): ModulePathManagerInterface
    {
        return $this->modulePathManager ??= $this->instanceManager->getInstance(ModulePathManagerInterface::class);
    }
    final public function setModulePathHelpers(ModulePathHelpersInterface $modulePathHelpers): void
    {
        $this->modulePathHelpers = $modulePathHelpers;
    }
    final protected function getModulePathHelpers(): ModulePathHelpersInterface
    {
        return $this->modulePathHelpers ??= $this->instanceManager->getInstance(ModulePathHelpersInterface::class);
    }

    public function addModuleFilter(ModuleFilterInterface $moduleFilter): void
    {
        $this->modulefilters[$moduleFilter->getName()] = $moduleFilter;
    }

    protected function init(): void
    {
        // Lazy initialize so that we can inject all the moduleFilters before checking the selected one
        $this->selected_filter_name = $this->selected_filter_name ?? $this->getSelectedModuleFilterName();
        if ($this->selected_filter_name) {
            $this->selected_filter = $this->modulefilters[$this->selected_filter_name];

            // Initialize only if we are intending to filter modules. This way, passing modulefilter=somewrongpath will return an empty array, meaning to not render anything
            $this->not_excluded_module_sets = $this->not_excluded_module_sets_as_string = array();
        }
        $this->initialized = true;
    }

    /**
     * The selected filter can be set from outside by the engine
     */
    public function setSelectedModuleFilterName(string $selectedModuleFilterName): void
    {
        $this->selected_filter_name = $selectedModuleFilterName;
    }

    public function getSelectedModuleFilterName(): ?string
    {
        if ($this->selected_filter_name) {
            return $this->selected_filter_name;
        }
        /** @var RootComponentConfiguration */
        $rootComponentConfiguration = App::getComponent(RootModule::class)->getConfiguration();
        if (!$rootComponentConfiguration->enablePassingStateViaRequest()) {
            return null;
        }

        // Only valid if there's a corresponding moduleFilter
        $selectedModuleFilterName = Request::getModuleFilter();
        if ($selectedModuleFilterName !== null && in_array($selectedModuleFilterName, array_keys($this->modulefilters))) {
            return $selectedModuleFilterName;
        }

        return null;
    }

    public function getNotExcludedModuleSets(): ?array
    {
        // It shall be used for requestmeta.rendermodules, to know from which modules the client must start rendering
        return $this->not_excluded_module_sets;
    }

    public function neverExclude($neverExclude): void
    {
        $this->neverExclude = $neverExclude;
    }

    public function excludeModule(array $module, array &$props): bool
    {
        if (!$this->initialized) {
            $this->init();
        }
        if ($this->selected_filter_name) {
            if ($this->neverExclude) {
                return false;
            }
            if (!is_null($this->not_excluded_ancestor_module)) {
                return false;
            }

            return $this->selected_filter->excludeModule($module, $props);
        }

        return false;
    }

    public function removeExcludedSubmodules(array $module, array $submodules): array
    {
        if (!$this->initialized) {
            $this->init();
        }
        if ($this->selected_filter_name) {
            if ($this->neverExclude) {
                return $submodules;
            }

            return $this->selected_filter->removeExcludedSubmodules($module, $submodules);
        }

        return $submodules;
    }

    /**
     * The `prepare` function advances the modulepath one level down, when interating into the submodules, and then calling `restore` the value goes one level up again
     */
    public function prepareForPropagation(array $module, array &$props): void
    {
        if (!$this->initialized) {
            $this->init();
        }
        if ($this->selected_filter_name) {
            if (!$this->neverExclude && is_null($this->not_excluded_ancestor_module) && $this->excludeModule($module, $props) === false) {
                // Set the current module as the one which is not excluded.
                $module_propagation_current_path = $this->getModulePathManager()->getPropagationCurrentPath();
                $module_propagation_current_path[] = $module;

                $this->not_excluded_ancestor_module = $this->getModulePathHelpers()->stringifyModulePath($module_propagation_current_path);

                // Add it to the list of not-excluded modules
                if (!in_array($this->not_excluded_ancestor_module, $this->not_excluded_module_sets_as_string)) {
                    $this->not_excluded_module_sets_as_string[] = $this->not_excluded_ancestor_module;
                    $this->not_excluded_module_sets[] = $module_propagation_current_path;
                }
            }

            $this->selected_filter->prepareForPropagation($module, $props);
        }

        // Add the module to the path
        $this->getModulePathManager()->prepareForPropagation($module, $props);
    }
    public function restoreFromPropagation(array $module, array &$props): void
    {
        if (!$this->initialized) {
            $this->init();
        }

        // Remove the module from the path
        $this->getModulePathManager()->restoreFromPropagation($module, $props);

        if ($this->selected_filter_name) {
            if (!$this->neverExclude && !is_null($this->not_excluded_ancestor_module) && $this->excludeModule($module, $props) === false) {
                $module_propagation_current_path = $this->getModulePathManager()->getPropagationCurrentPath();
                $module_propagation_current_path[] = $module;

                // If the current module was set as the one not excluded, then reset it
                if ($this->not_excluded_ancestor_module == $this->getModulePathHelpers()->stringifyModulePath($module_propagation_current_path)) {
                    $this->not_excluded_ancestor_module = null;
                }
            }

            $this->selected_filter->restoreFromPropagation($module, $props);
        }
    }
}
