<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModulePath;

class ModulePathManager implements ModulePathManagerInterface
{
    /**
     * @var array[]|null
     */
    protected ?array $propagation_current_path = null;

    public function getPropagationCurrentPath(): ?array
    {
        return $this->propagation_current_path;
    }

    public function setPropagationCurrentPath(?array $propagation_current_path = null): void
    {
        $this->propagation_current_path = $propagation_current_path;
    }

    /**
     * The `prepare` function advances the componentPath one level down, when interating into the submodules, and then calling `restore` the value goes one level up again
     */
    public function prepareForPropagation(array $component, array &$props): void
    {
        // Add the component to the path
        // Prepare for the subcomponent, going one level down, and adding it to the current path
        // We add $component instead of the first element from $this->propagation_unsettled_paths, so that calculating $this->propagation_current_path works also when not doing ?componentPaths=...
        $this->propagation_current_path[] = $component;
    }
    public function restoreFromPropagation(array $component, array &$props): void
    {
        // Remove the component to the path
        array_pop($this->propagation_current_path);
    }
}
