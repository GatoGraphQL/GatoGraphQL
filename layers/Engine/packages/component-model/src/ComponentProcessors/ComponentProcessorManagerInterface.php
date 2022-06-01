<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;

interface ComponentProcessorManagerInterface
{
    /**
     * @deprecated Use the Service Container instead
     */
    public function overrideProcessorClass(string $overrideClass, string $withClass, array $forItemNames): void;
    public function getProcessor(Component $component): ComponentProcessorInterface;
}
