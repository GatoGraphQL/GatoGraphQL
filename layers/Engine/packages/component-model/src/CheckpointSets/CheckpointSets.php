<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointSets;

use PoP\ComponentModel\Checkpoints\MutationCheckpoint;

class CheckpointSets
{
    const CAN_EXECUTE_MUTATIONS = array(
        [MutationCheckpoint::class, MutationCheckpoint::ENABLED_MUTATIONS],
    );
}
