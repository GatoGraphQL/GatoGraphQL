<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

interface ComponentHelpersInterface
{
    public function getComponentFullName(\PoP\ComponentModel\Component\Component $component): string;
    public function getComponentFromFullName(string $componentFullName): ?array;
    public function getComponentOutputName(\PoP\ComponentModel\Component\Component $component): string;
    public function getComponentFromOutputName(string $componentOutputName): ?array;
}
