<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentPath;

use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Facades\ComponentPath\ComponentPathHelpersFacade;
use PoP\ComponentModel\Modules\ModuleHelpersInterface;
use PoP\ComponentModel\Tokens\ComponentPath;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\Services\BasicServiceTrait;

class ComponentPathHelpers implements ComponentPathHelpersInterface
{
    use BasicServiceTrait;

    private ?ComponentPathManagerInterface $modulePathManager = null;
    private ?ModuleHelpersInterface $moduleHelpers = null;

    final public function setComponentPathManager(ComponentPathManagerInterface $modulePathManager): void
    {
        $this->modulePathManager = $modulePathManager;
    }
    final protected function getComponentPathManager(): ComponentPathManagerInterface
    {
        return $this->modulePathManager ??= $this->instanceManager->getInstance(ComponentPathManagerInterface::class);
    }
    final public function setModuleHelpers(ModuleHelpersInterface $moduleHelpers): void
    {
        $this->moduleHelpers = $moduleHelpers;
    }
    final protected function getModuleHelpers(): ModuleHelpersInterface
    {
        return $this->moduleHelpers ??= $this->instanceManager->getInstance(ModuleHelpersInterface::class);
    }

    public function getStringifiedModulePropagationCurrentPath(array $component): string
    {
        $module_propagation_current_path = $this->getComponentPathManager()->getPropagationCurrentPath();
        $module_propagation_current_path[] = $component;
        return $this->stringifyComponentPath($module_propagation_current_path);
    }

    public function stringifyComponentPath(array $componentPath): string
    {
        return implode(
            ComponentPath::COMPONENT_SEPARATOR,
            array_map(
                [$this->getModuleHelpers(), 'getModuleOutputName'],
                $componentPath
            )
        );
    }

    public function recastComponentPath(string $componentPath_as_string): array
    {
        return array_map(
            [$this->getModuleHelpers(), 'getModuleFromOutputName'],
            explode(
                ComponentPath::COMPONENT_SEPARATOR,
                $componentPath_as_string
            )
        );
    }

    /**
     * @return array<string[]>
     */
    public function getComponentPaths(): array
    {
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$rootModuleConfiguration->enablePassingStateViaRequest()) {
            return [];
        }

        $paths = Request::getComponentPaths();
        if (!$paths) {
            return [];
        }

        // If any path is a substring from another one, then it is its root, and only this one will be taken into account, so remove its substrings
        // Eg: toplevel.pagesection-top is substring of toplevel, so if passing these 2 componentPaths, keep only toplevel
        // Check that the last character is ".", to avoid toplevel1 to be removed
        $paths = array_filter(
            $paths,
            function ($item) use ($paths) {
                foreach ($paths as $path) {
                    if (strlen($item) > strlen($path) && str_starts_with($item, $path) && $item[strlen($path)] == ComponentPath::COMPONENT_SEPARATOR) {
                        return false;
                    }
                }

                return true;
            }
        );

        $componentPaths = [];
        foreach ($paths as $path) {
            // Each path must be converted to an array of the modules
            $componentPaths[] = ComponentPathHelpersFacade::getInstance()->recastComponentPath($path);
        }

        return $componentPaths;
    }
}
