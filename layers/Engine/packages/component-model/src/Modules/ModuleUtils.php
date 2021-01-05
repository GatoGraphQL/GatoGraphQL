<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

use PoP\ComponentModel\ItemProcessors\ProcessorItemUtils;

class ModuleUtils
{
    public static function getModuleFullName(array $module): string
    {
        return ProcessorItemUtils::getItemFullName($module);
    }
    public static function getModuleFromFullName(string $moduleFullName): ?array
    {
        return ProcessorItemUtils::getItemFromFullName($moduleFullName);
    }
    public static function getModuleOutputName(array $module): string
    {
        return ProcessorItemUtils::getItemOutputName($module, DefinitionGroups::MODULES);
    }
    public static function getModuleFromOutputName(string $moduleOutputName): ?array
    {
        return ProcessorItemUtils::getItemFromOutputName($moduleOutputName, DefinitionGroups::MODULES);
    }
}
