<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentPath;

use PoP\ComponentModel\Component\Component;

interface ComponentPathHelpersInterface
{
    public function getStringifiedModulePropagationCurrentPath(Component $component): string;
    /**
     * @param Component[] $componentPath
     */
    public function stringifyComponentPath(array $componentPath): string;
    /**
     * @return array<Component|null>
     */
    public function recastComponentPath(string $componentPath_as_string): array;
    /**
     * @return array<array<Component|null>>
     */
    public function getComponentPaths(): array;
}
