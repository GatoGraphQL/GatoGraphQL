<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

interface FilterInputProcessorManagerInterface
{
    public function overrideProcessorClass(string $overrideClass, string $withClass, array $forItemNames): void;
    public function getItemProcessor(array $item);
    public function getProcessor(array $item);
}
