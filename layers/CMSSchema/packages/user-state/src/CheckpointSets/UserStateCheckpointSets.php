<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\CheckpointSets;

use PoP\Engine\Checkpoints\RequestCheckpoint;
use PoPCMSSchema\UserState\Checkpoints\UserStateCheckpoint;

class UserStateCheckpointSets
{
    const NOTLOGGEDIN = array(
        [RequestCheckpoint::class, RequestCheckpoint::DOING_POST],
        [UserStateCheckpoint::class, UserStateCheckpoint::USERNOTLOGGEDIN],
    );
    const LOGGEDIN_STATIC = array(
        [RequestCheckpoint::class, RequestCheckpoint::DOING_POST],
        [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    );
    const LOGGEDIN_DATAFROMSERVER = array(
        [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    );
}
