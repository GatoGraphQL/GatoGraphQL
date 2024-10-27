<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\Checkpoints;

use PoP\ComponentModel\Checkpoints\AbstractAggregateCheckpoint;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\Engine\Checkpoints\DoingPostCheckpoint;
use PoPCMSSchema\UserState\Checkpoints\UserNotLoggedInCheckpoint;

class DoingPostUserNotLoggedInAggregateCheckpoint extends AbstractAggregateCheckpoint
{
    private ?UserNotLoggedInCheckpoint $userNotLoggedInCheckpoint = null;
    private ?DoingPostCheckpoint $doingPostCheckpoint = null;

    final protected function getUserNotLoggedInCheckpoint(): UserNotLoggedInCheckpoint
    {
        if ($this->userNotLoggedInCheckpoint === null) {
            /** @var UserNotLoggedInCheckpoint */
            $userNotLoggedInCheckpoint = $this->instanceManager->getInstance(UserNotLoggedInCheckpoint::class);
            $this->userNotLoggedInCheckpoint = $userNotLoggedInCheckpoint;
        }
        return $this->userNotLoggedInCheckpoint;
    }
    final protected function getDoingPostCheckpoint(): DoingPostCheckpoint
    {
        if ($this->doingPostCheckpoint === null) {
            /** @var DoingPostCheckpoint */
            $doingPostCheckpoint = $this->instanceManager->getInstance(DoingPostCheckpoint::class);
            $this->doingPostCheckpoint = $doingPostCheckpoint;
        }
        return $this->doingPostCheckpoint;
    }

    /**
     * @return CheckpointInterface[]
     */
    protected function getCheckpoints(): array
    {
        return [
            $this->getDoingPostCheckpoint(),
            $this->getUserNotLoggedInCheckpoint(),
        ];
    }
}
