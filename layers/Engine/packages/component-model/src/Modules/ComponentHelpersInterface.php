<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

interface ComponentHelpersInterface
{
    public function getComponentFullName(\PoP\ComponentModel\Component\Component $component): string;
    public function getComponentFromFullName(string $componentFullName): ?\PoP\ComponentModel\Component\Component;
    public function getComponentOutputName(\PoP\ComponentModel\Component\Component $component): string;
    public function getComponentFromOutputName(string $componentOutputName): ?\PoP\ComponentModel\Component\Component;
}
