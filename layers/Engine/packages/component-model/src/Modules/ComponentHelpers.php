<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

use PoP\ComponentModel\ItemProcessors\ProcessorItemUtils;
use PoP\Root\Services\BasicServiceTrait;

class ComponentHelpers implements ComponentHelpersInterface
{
    use BasicServiceTrait;

    public function getComponentFullName(\PoP\ComponentModel\Component\Component $component): string
    {
        return ProcessorItemUtils::getItemFullName($component);
    }
    public function getComponentFromFullName(string $componentFullName): ?\PoP\ComponentModel\Component\Component
    {
        return ProcessorItemUtils::getItemFromFullName($componentFullName);
    }
    public function getComponentOutputName(\PoP\ComponentModel\Component\Component $component): string
    {
        return ProcessorItemUtils::getItemOutputName($component, DefinitionGroups::COMPONENTS);
    }
    public function getComponentFromOutputName(string $componentOutputName): ?\PoP\ComponentModel\Component\Component
    {
        return ProcessorItemUtils::getItemFromOutputName($componentOutputName, DefinitionGroups::COMPONENTS);
    }
}
