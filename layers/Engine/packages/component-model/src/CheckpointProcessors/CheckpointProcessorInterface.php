<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

interface CheckpointProcessorInterface
{
    /**
     * @return array<array>
     */
    public function getCheckpointsToProcess(): array;

    /**
     * @return boolean `true` if successful, `false` otherwise
     */
    public function process(array $checkpoint);
}
