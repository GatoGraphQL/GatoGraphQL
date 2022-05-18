<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

interface ModuleHelpersInterface
{
    public function getModuleFullName(array $componentVariation): string;
    public function getModuleFromFullName(string $moduleFullName): ?array;
    public function getModuleOutputName(array $componentVariation): string;
    public function getModuleFromOutputName(string $moduleOutputName): ?array;
}
