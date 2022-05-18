<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

use PoP\ComponentModel\ItemProcessors\ProcessorItemUtils;
use PoP\Root\Services\BasicServiceTrait;

class ModuleHelpers implements ModuleHelpersInterface
{
    use BasicServiceTrait;

    public function getModuleFullName(array $componentVariation): string
    {
        return ProcessorItemUtils::getItemFullName($componentVariation);
    }
    public function getModuleFromFullName(string $moduleFullName): ?array
    {
        return ProcessorItemUtils::getItemFromFullName($moduleFullName);
    }
    public function getModuleOutputName(array $componentVariation): string
    {
        return ProcessorItemUtils::getItemOutputName($componentVariation, DefinitionGroups::MODULES);
    }
    public function getModuleFromOutputName(string $moduleOutputName): ?array
    {
        return ProcessorItemUtils::getItemFromOutputName($moduleOutputName, DefinitionGroups::MODULES);
    }
}
