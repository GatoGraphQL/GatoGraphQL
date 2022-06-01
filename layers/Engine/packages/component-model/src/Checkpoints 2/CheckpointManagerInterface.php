<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Checkpoints;

interface CheckpointManagerInterface
{
    /**
     * @deprecated Use the Service Container instead
     */
    public function overrideProcessorClass(string $overrideClass, string $withClass, array $forItemNames): void;
    public function getProcessor(array $item): CheckpointInterface;
}
