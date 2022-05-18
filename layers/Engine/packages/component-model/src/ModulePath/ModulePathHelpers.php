<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModulePath;

use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Facades\ModulePath\ModulePathHelpersFacade;
use PoP\ComponentModel\Modules\ModuleHelpersInterface;
use PoP\ComponentModel\Tokens\ModulePath;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\Services\BasicServiceTrait;

class ModulePathHelpers implements ModulePathHelpersInterface
{
    use BasicServiceTrait;

    private ?ModulePathManagerInterface $modulePathManager = null;
    private ?ModuleHelpersInterface $moduleHelpers = null;

    final public function setModulePathManager(ModulePathManagerInterface $modulePathManager): void
    {
        $this->modulePathManager = $modulePathManager;
    }
    final protected function getModulePathManager(): ModulePathManagerInterface
    {
        return $this->modulePathManager ??= $this->instanceManager->getInstance(ModulePathManagerInterface::class);
    }
    final public function setModuleHelpers(ModuleHelpersInterface $moduleHelpers): void
    {
        $this->moduleHelpers = $moduleHelpers;
    }
    final protected function getModuleHelpers(): ModuleHelpersInterface
    {
        return $this->moduleHelpers ??= $this->instanceManager->getInstance(ModuleHelpersInterface::class);
    }

    public function getStringifiedModulePropagationCurrentPath(array $componentVariation): string
    {
        $module_propagation_current_path = $this->getModulePathManager()->getPropagationCurrentPath();
        $module_propagation_current_path[] = $componentVariation;
        return $this->stringifyModulePath($module_propagation_current_path);
    }

    public function stringifyModulePath(array $modulepath): string
    {
        return implode(
            ModulePath::MODULE_SEPARATOR,
            array_map(
                [$this->getModuleHelpers(), 'getModuleOutputName'],
                $modulepath
            )
        );
    }

    public function recastModulePath(string $modulepath_as_string): array
    {
        return array_map(
            [$this->getModuleHelpers(), 'getModuleFromOutputName'],
            explode(
                ModulePath::MODULE_SEPARATOR,
                $modulepath_as_string
            )
        );
    }

    /**
     * @return array<string[]>
     */
    public function getModulePaths(): array
    {
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$rootModuleConfiguration->enablePassingStateViaRequest()) {
            return [];
        }

        $paths = Request::getModulePaths();
        if (!$paths) {
            return [];
        }

        // If any path is a substring from another one, then it is its root, and only this one will be taken into account, so remove its substrings
        // Eg: toplevel.pagesection-top is substring of toplevel, so if passing these 2 modulepaths, keep only toplevel
        // Check that the last character is ".", to avoid toplevel1 to be removed
        $paths = array_filter(
            $paths,
            function ($item) use ($paths) {
                foreach ($paths as $path) {
                    if (strlen($item) > strlen($path) && str_starts_with($item, $path) && $item[strlen($path)] == ModulePath::MODULE_SEPARATOR) {
                        return false;
                    }
                }

                return true;
            }
        );

        $modulePaths = [];
        foreach ($paths as $path) {
            // Each path must be converted to an array of the modules
            $modulePaths[] = ModulePathHelpersFacade::getInstance()->recastModulePath($path);
        }

        return $modulePaths;
    }
}
