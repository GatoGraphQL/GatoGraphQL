<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModulePath;

use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\ComponentModel\Tokens\ModulePath;
use Symfony\Contracts\Service\Attribute\Required;

class ModulePathHelpers implements ModulePathHelpersInterface
{
    use BasicServiceTrait;

    private ?ModulePathManagerInterface $modulePathManager = null;

    public function setModulePathManager(ModulePathManagerInterface $modulePathManager): void
    {
        $this->modulePathManager = $modulePathManager;
    }
    protected function getModulePathManager(): ModulePathManagerInterface
    {
        return $this->modulePathManager ??= $this->instanceManager->getInstance(ModulePathManagerInterface::class);
    }

    //#[Required]
    final public function autowireModulePathHelpers(ModulePathManagerInterface $modulePathManager): void
    {
        $this->modulePathManager = $modulePathManager;
    }

    public function getStringifiedModulePropagationCurrentPath(array $module)
    {
        $module_propagation_current_path = $this->getModulePathManager()->getPropagationCurrentPath();
        $module_propagation_current_path[] = $module;
        return $this->stringifyModulePath($module_propagation_current_path);
    }

    public function stringifyModulePath(array $modulepath)
    {
        return implode(
            ModulePath::MODULE_SEPARATOR,
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
                ModulePath::MODULE_SEPARATOR,
                $modulepath_as_string
            )
        );
    }
}
