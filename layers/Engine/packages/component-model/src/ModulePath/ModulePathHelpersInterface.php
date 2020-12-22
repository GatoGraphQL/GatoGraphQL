<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModulePath;

interface ModulePathHelpersInterface
{
    public function getStringifiedModulePropagationCurrentPath(array $module);
    public function stringifyModulePath(array $modulepath);
    public function recastModulePath(string $modulepath_as_string);
}
