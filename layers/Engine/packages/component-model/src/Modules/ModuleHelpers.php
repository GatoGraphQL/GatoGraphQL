<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

use PoP\ComponentModel\ItemProcessors\ProcessorItemUtils;
use PoP\Root\Services\BasicServiceTrait;

class ModuleHelpers implements ModuleHelpersInterface
{
    use BasicServiceTrait;

    public function getModuleFullName(array $module): string
    {
        return ProcessorItemUtils::getItemFullName($module);
    }
    public function getModuleFromFullName(string $moduleFullName): ?array
    {
        return ProcessorItemUtils::getItemFromFullName($moduleFullName);
    }
    public function getModuleOutputName(array $module): string
    {
        return ProcessorItemUtils::getItemOutputName($module, DefinitionGroups::MODULES);
    }
    public function getModuleFromOutputName(string $moduleOutputName): ?array
    {
        return ProcessorItemUtils::getItemFromOutputName($moduleOutputName, DefinitionGroups::MODULES);
    }
}
