<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentHelpers;

use PoP\ComponentModel\Component\Component;
interface ComponentHelpersInterface
{
    public function getComponentFullName(Component $component): string;
    public function getComponentFromFullName(string $componentFullName): ?Component;
    public function getComponentOutputName(Component $component): string;
    public function getComponentFromOutputName(string $componentOutputName): ?Component;
}
