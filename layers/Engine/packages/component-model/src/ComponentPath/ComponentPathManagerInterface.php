<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentPath;

use PoP\ComponentModel\Component\Component;

interface ComponentPathManagerInterface
{
    /**
     * @return Component[]|null
     */
    public function getPropagationCurrentPath(): ?array;
    /**
     * @param Component[]|null $propagation_current_path
     */
    public function setPropagationCurrentPath(?array $propagation_current_path = null): void;
    /**
     * The `prepare` function advances the componentPath one level down, when iterating into the subcomponents, and then calling `restore` the value goes one level up again
     * @param array<string,mixed> $props
     */
    public function prepareForPropagation(Component $component, array &$props): void;
    /**
     * @param array<string,mixed> $props
     */
    public function restoreFromPropagation(Component $component, array &$props): void;
}
