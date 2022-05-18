<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

use PoP\ComponentModel\ItemProcessors\ProcessorItemUtils;
use PoP\Root\Services\BasicServiceTrait;

class ModuleHelpers implements ModuleHelpersInterface
{
    use BasicServiceTrait;

    public function getModuleFullName(array $component): string
    {
        return ProcessorItemUtils::getItemFullName($component);
    }
    public function getModuleFromFullName(string $componentFullName): ?array
    {
        return ProcessorItemUtils::getItemFromFullName($componentFullName);
    }
    public function getModuleOutputName(array $component): string
    {
        return ProcessorItemUtils::getItemOutputName($component, DefinitionGroups::COMPONENTS);
    }
    public function getModuleFromOutputName(string $moduleOutputName): ?array
    {
        return ProcessorItemUtils::getItemFromOutputName($moduleOutputName, DefinitionGroups::COMPONENTS);
    }
}
