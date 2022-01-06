<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModulePath;

interface ModulePathHelpersInterface
{
    public function getStringifiedModulePropagationCurrentPath(array $module): string;
    public function stringifyModulePath(array $modulepath): string;
    public function recastModulePath(string $modulepath_as_string): array;
}
