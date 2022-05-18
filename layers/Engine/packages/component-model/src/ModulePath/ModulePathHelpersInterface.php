<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModulePath;

interface ModulePathHelpersInterface
{
    public function getStringifiedModulePropagationCurrentPath(array $componentVariation): string;
    public function stringifyModulePath(array $componentVariationPath): string;
    public function recastModulePath(string $componentVariationPath_as_string): array;
    /**
     * @return array<string[]>
     */
    public function getModulePaths(): array;
}
