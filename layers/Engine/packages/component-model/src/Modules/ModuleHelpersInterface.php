<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

interface ModuleHelpersInterface
{
    public function getModuleFullName(array $component): string;
    public function getModuleFromFullName(string $moduleFullName): ?array;
    public function getModuleOutputName(array $component): string;
    public function getModuleFromOutputName(string $moduleOutputName): ?array;
}
