<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModulePath;

interface ModulePathHelpersInterface
{
    public function getStringifiedModulePropagationCurrentPath(array $component): string;
    public function stringifyModulePath(array $componentPath): string;
    public function recastModulePath(string $componentPath_as_string): array;
    /**
     * @return array<string[]>
     */
    public function getModulePaths(): array;
}
