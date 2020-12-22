<?php

declare(strict_types=1);

namespace PoPSchema\UserState\CheckpointSets;

use PoP\Engine\CheckpointProcessors\RequestCheckpointProcessor;
use PoPSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor;

class UserStateCheckpointSets
{
    const NOTLOGGEDIN = array(
        [RequestCheckpointProcessor::class, RequestCheckpointProcessor::DOING_POST],
        [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERNOTLOGGEDIN],
    );
    const LOGGEDIN_STATIC = array(
        [RequestCheckpointProcessor::class, RequestCheckpointProcessor::DOING_POST],
        [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    );
    const LOGGEDIN_DATAFROMSERVER = array(
        [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    );
}
