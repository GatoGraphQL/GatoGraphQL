<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModulePath;

use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Facades\ModulePath\ModulePathHelpersFacade;
use PoP\ComponentModel\Tokens\ModulePath;
use PoP\Root\App;
use PoP\Root\Component as RootComponent;
use PoP\Root\ComponentConfiguration as RootComponentConfiguration;

class ModulePathUtils
{
    public static function getModulePaths(): array
    {
        /** @var RootComponentConfiguration */
        $rootComponentConfiguration = App::getComponent(RootComponent::class)->getConfiguration();
        if (!$rootComponentConfiguration->enablePassingStateViaRequest()) {
            return [];
        }

        $paths = $_REQUEST[Params::MODULEPATHS];
        if ($paths === null) {
            return [];
        }

        $modulePaths = [];
        if (!is_array($paths)) {
            $paths = array($paths);
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

        foreach ($paths as $path) {
            // Each path must be converted to an array of the modules
            $modulePaths[] = ModulePathHelpersFacade::getInstance()->recastModulePath($path);
        }

        return $modulePaths;
    }
}
