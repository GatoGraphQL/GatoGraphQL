<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentPath;

use PoP\ComponentModel\Component\Component;
interface ComponentPathHelpersInterface
{
    public function getStringifiedModulePropagationCurrentPath(Component $component): string;
    public function stringifyComponentPath(array $componentPath): string;
    public function recastComponentPath(string $componentPath_as_string): array;
    /**
     * @return array<string[]>
     */
    public function getComponentPaths(): array;
}
