<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

interface ComponentHelpersInterface
{
    public function getModuleFullName(array $component): string;
    public function getModuleFromFullName(string $componentFullName): ?array;
    public function getComponentOutputName(array $component): string;
    public function getModuleFromOutputName(string $moduleOutputName): ?array;
}
