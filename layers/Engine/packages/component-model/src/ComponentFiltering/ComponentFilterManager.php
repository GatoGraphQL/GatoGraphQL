<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentFiltering;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentFilters\ComponentFilterInterface;
use PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface;
use PoP\ComponentModel\ComponentPath\ComponentPathManagerInterface;
use PoP\ComponentModel\Configuration\Request;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\Services\BasicServiceTrait;

class ComponentFilterManager implements ComponentFilterManagerInterface
{
    use BasicServiceTrait;

    protected ?string $selected_filter_name = null;
    private ?ComponentFilterInterface $selected_filter = null;
    /**
     * @var array<string,ComponentFilterInterface>
     */
    protected array $componentfilters = [];
    protected bool $initialized = false;
    /**
     * From the moment in which a component is not excluded,
     * every component from then on must also be included
     */
    protected ?string $not_excluded_ancestor_component = null;
    /**
     * @var array<mixed[]>|null
     */
    protected ?array $not_excluded_component_sets = null;
    /**
     * @var string[]|null
     */
    protected ?array $not_excluded_component_sets_as_string;
    /**
     * When targeting components in pop-engine.php (eg: when doing ->getObjectIDs())
     * those components are already and always included, so no need to check
     * for their ancestors or anything
     */
    protected bool $neverExclude = false;

    private ?ComponentPathManagerInterface $componentPathManager = null;
    private ?ComponentPathHelpersInterface $componentPathHelpers = null;

    final protected function getComponentPathManager(): ComponentPathManagerInterface
    {
        if ($this->componentPathManager === null) {
            /** @var ComponentPathManagerInterface */
            $componentPathManager = $this->instanceManager->getInstance(ComponentPathManagerInterface::class);
            $this->componentPathManager = $componentPathManager;
        }
        return $this->componentPathManager;
    }
    final protected function getComponentPathHelpers(): ComponentPathHelpersInterface
    {
        if ($this->componentPathHelpers === null) {
            /** @var ComponentPathHelpersInterface */
            $componentPathHelpers = $this->instanceManager->getInstance(ComponentPathHelpersInterface::class);
            $this->componentPathHelpers = $componentPathHelpers;
        }
        return $this->componentPathHelpers;
    }

    public function addComponentFilter(ComponentFilterInterface $componentFilter): void
    {
        $this->componentfilters[$componentFilter->getName()] = $componentFilter;
    }

    protected function init(): void
    {
        // Lazy initialize so that we can inject all the componentFilters before checking the selected one
        $this->selected_filter_name = $this->selected_filter_name ?? $this->getSelectedComponentFilterName();
        if ($this->selected_filter_name) {
            $this->selected_filter = $this->componentfilters[$this->selected_filter_name] ?? null;

            // Initialize only if we are intending to filter components. This way, passing componentFilter=somewrongpath will return an empty array, meaning to not render anything
            $this->not_excluded_component_sets = $this->not_excluded_component_sets_as_string = array();
        }
        $this->initialized = true;
    }

    /**
     * The selected filter can be set from outside by the engine
     */
    public function setSelectedComponentFilterName(string $selectedComponentFilterName): void
    {
        $this->selected_filter_name = $selectedComponentFilterName;
    }

    public function getSelectedComponentFilterName(): ?string
    {
        if ($this->selected_filter_name) {
            return $this->selected_filter_name;
        }
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$rootModuleConfiguration->enablePassingStateViaRequest()) {
            return null;
        }

        // Only valid if there's a corresponding componentFilter
        $selectedComponentFilterName = Request::getComponentFilter();
        if ($selectedComponentFilterName !== null && in_array($selectedComponentFilterName, array_keys($this->componentfilters))) {
            return $selectedComponentFilterName;
        }

        return null;
    }

    /**
     * @return array<mixed[]>|null
     */
    public function getNotExcludedComponentSets(): ?array
    {
        // It shall be used for requestmeta.rendercomponents, to know from which components the client must start rendering
        return $this->not_excluded_component_sets;
    }

    public function setNeverExclude(bool $neverExclude): void
    {
        $this->neverExclude = $neverExclude;
    }

    /**
     * @param array<string,mixed> $props
     */
    public function excludeSubcomponent(Component $component, array &$props): bool
    {
        if (!$this->initialized) {
            $this->init();
        }
        if ($this->selected_filter !== null) {
            if ($this->neverExclude) {
                return false;
            }
            if ($this->not_excluded_ancestor_component !== null) {
                return false;
            }

            return $this->selected_filter->excludeSubcomponent($component, $props);
        }

        return false;
    }

    /**
     * @param Component[] $subcomponents
     * @return Component[]
     */
    public function removeExcludedSubcomponents(Component $component, array $subcomponents): array
    {
        if (!$this->initialized) {
            $this->init();
        }
        if ($this->selected_filter !== null) {
            if ($this->neverExclude) {
                return $subcomponents;
            }

            return $this->selected_filter->removeExcludedSubcomponents($component, $subcomponents);
        }

        return $subcomponents;
    }

    /**
     * The `prepare` function advances the componentPath one level down, when iterating into the subcomponents, and then calling `restore` the value goes one level up again
     * @param array<string,mixed> $props
     */
    public function prepareForPropagation(Component $component, array &$props): void
    {
        if (!$this->initialized) {
            $this->init();
        }
        if ($this->selected_filter !== null) {
            if (!$this->neverExclude && $this->not_excluded_ancestor_component === null && $this->excludeSubcomponent($component, $props) === false) {
                // Set the current component as the one which is not excluded.
                $component_propagation_current_path = $this->getComponentPathManager()->getPropagationCurrentPath();
                $component_propagation_current_path[] = $component;

                $this->not_excluded_ancestor_component = $this->getComponentPathHelpers()->stringifyComponentPath($component_propagation_current_path);

                // Add it to the list of not-excluded components
                /** @var string[] */
                $not_excluded_component_sets_as_string = $this->not_excluded_component_sets_as_string;
                if (!in_array($this->not_excluded_ancestor_component, $not_excluded_component_sets_as_string)) {
                    $this->not_excluded_component_sets_as_string[] = $this->not_excluded_ancestor_component;
                    $this->not_excluded_component_sets[] = $component_propagation_current_path;
                }
            }

            $this->selected_filter->prepareForPropagation($component, $props);
        }

        // Add the component to the path
        $this->getComponentPathManager()->prepareForPropagation($component, $props);
    }
    /**
     * @param array<string,mixed> $props
     */
    public function restoreFromPropagation(Component $component, array &$props): void
    {
        if (!$this->initialized) {
            $this->init();
        }

        // Remove the component from the path
        $this->getComponentPathManager()->restoreFromPropagation($component, $props);

        if ($this->selected_filter !== null) {
            if (!$this->neverExclude && $this->not_excluded_ancestor_component !== null && $this->excludeSubcomponent($component, $props) === false) {
                $component_propagation_current_path = $this->getComponentPathManager()->getPropagationCurrentPath();
                $component_propagation_current_path[] = $component;

                // If the current component was set as the one not excluded, then reset it
                if ($this->not_excluded_ancestor_component === $this->getComponentPathHelpers()->stringifyComponentPath($component_propagation_current_path)) {
                    $this->not_excluded_ancestor_component = null;
                }
            }

            $this->selected_filter->restoreFromPropagation($component, $props);
        }
    }
}
