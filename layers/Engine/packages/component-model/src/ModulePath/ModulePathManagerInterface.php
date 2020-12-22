<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModulePath;

interface ModulePathManagerInterface
{
    public function getPropagationCurrentPath(): ?array;
    public function setPropagationCurrentPath(?array $propagation_current_path = null): void;
    /**
     * The `prepare` function advances the modulepath one level down, when interating into the submodules, and then calling `restore` the value goes one level up again
     */
    public function prepareForPropagation(array $module, array &$props): void;
    public function restoreFromPropagation(array $module, array &$props): void;
}
