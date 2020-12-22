<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModulePath;
use PoP\ComponentModel\Modules\ModuleUtils;

class ModulePathHelpers implements ModulePathHelpersInterface
{
    protected ModulePathManagerInterface $modulePathManager;

    public function __construct(ModulePathManagerInterface $modulePathManager)
    {
        $this->modulePathManager = $modulePathManager;
    }

    public function getStringifiedModulePropagationCurrentPath(array $module)
    {
        $module_propagation_current_path = $this->modulePathManager->getPropagationCurrentPath();
        $module_propagation_current_path[] = $module;
        return $this->stringifyModulePath($module_propagation_current_path);
    }

    public function stringifyModulePath(array $modulepath)
    {
        return implode(
            POP_CONSTANT_MODULESTARTPATH_SEPARATOR,
            array_map(
                [ModuleUtils::class, 'getModuleOutputName'],
                $modulepath
            )
        );
    }

    public function recastModulePath(string $modulepath_as_string)
    {
        return array_map(
            [ModuleUtils::class, 'getModuleFromOutputName'],
            explode(
                POP_CONSTANT_MODULESTARTPATH_SEPARATOR,
                $modulepath_as_string
            )
        );
    }
}
