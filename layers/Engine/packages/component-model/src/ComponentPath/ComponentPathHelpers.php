<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentPath;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Facades\ComponentPath\ComponentPathHelpersFacade;
use PoP\ComponentModel\ComponentHelpers\ComponentHelpersInterface;
use PoP\ComponentModel\Tokens\ComponentPath;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\Services\BasicServiceTrait;

class ComponentPathHelpers implements ComponentPathHelpersInterface
{
    use BasicServiceTrait;

    private ?ComponentPathManagerInterface $componentPathManager = null;
    private ?ComponentHelpersInterface $componentHelpers = null;

    final protected function getComponentPathManager(): ComponentPathManagerInterface
    {
        if ($this->componentPathManager === null) {
            /** @var ComponentPathManagerInterface */
            $componentPathManager = $this->instanceManager->getInstance(ComponentPathManagerInterface::class);
            $this->componentPathManager = $componentPathManager;
        }
        return $this->componentPathManager;
    }
    final protected function getComponentHelpers(): ComponentHelpersInterface
    {
        if ($this->componentHelpers === null) {
            /** @var ComponentHelpersInterface */
            $componentHelpers = $this->instanceManager->getInstance(ComponentHelpersInterface::class);
            $this->componentHelpers = $componentHelpers;
        }
        return $this->componentHelpers;
    }

    public function getStringifiedModulePropagationCurrentPath(Component $component): string
    {
        $module_propagation_current_path = $this->getComponentPathManager()->getPropagationCurrentPath();
        $module_propagation_current_path[] = $component;
        return $this->stringifyComponentPath($module_propagation_current_path);
    }

    /**
     * @param Component[] $componentPath
     */
    public function stringifyComponentPath(array $componentPath): string
    {
        return implode(
            ComponentPath::COMPONENT_SEPARATOR,
            array_map(
                $this->getComponentHelpers()->getComponentOutputName(...),
                $componentPath
            )
        );
    }

    /**
     * @return array<Component|null>
     */
    public function recastComponentPath(string $componentPath_as_string): array
    {
        return array_map(
            $this->getComponentHelpers()->getComponentFromOutputName(...),
            explode(
                ComponentPath::COMPONENT_SEPARATOR,
                $componentPath_as_string
            )
        );
    }

    /**
     * @return array<array<Component|null>>
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
            function (string $item) use ($paths): bool {
                foreach ($paths as $path) {
                    if (strlen($item) > strlen($path) && str_starts_with($item, $path) && $item[strlen($path)] === ComponentPath::COMPONENT_SEPARATOR) {
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
