<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

use PoP\ComponentModel\ItemProcessors\ProcessorItemUtils;
use PoP\Root\Services\BasicServiceTrait;

class ComponentHelpers implements ComponentHelpersInterface
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
    public function getComponentOutputName(array $component): string
    {
        return ProcessorItemUtils::getItemOutputName($component, DefinitionGroups::COMPONENTS);
    }
    public function getModuleFromOutputName(string $componentOutputName): ?array
    {
        return ProcessorItemUtils::getItemFromOutputName($componentOutputName, DefinitionGroups::COMPONENTS);
    }
}
