<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointSets;

use PoP\ComponentModel\CheckpointProcessors\MutationCheckpointProcessor;

class CheckpointSets
{
    const CAN_EXECUTE_MUTATIONS = array(
        [MutationCheckpointProcessor::class, MutationCheckpointProcessor::ENABLED_MUTATIONS],
    );
}
