<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface ModuleProcessorManagerInterface
{
    /**
     * @deprecated Use the Service Container instead
     */
    public function overrideProcessorClass(string $overrideClass, string $withClass, array $forItemNames): void;
    public function getProcessor(array $item): ModuleProcessorInterface;
}
