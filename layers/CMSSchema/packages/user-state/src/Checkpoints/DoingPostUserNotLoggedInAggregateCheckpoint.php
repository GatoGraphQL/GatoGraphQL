<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\Checkpoints;

use PoP\ComponentModel\Checkpoints\AbstractAggregateCheckpoint;
use PoP\Engine\Checkpoints\DoingPostCheckpoint;
use PoPCMSSchema\UserState\Checkpoints\UserNotLoggedInCheckpoint;

class DoingPostUserNotLoggedInAggregateCheckpoint extends AbstractAggregateCheckpoint
{
    private ?UserNotLoggedInCheckpoint $userNotLoggedInCheckpoint = null;
    private ?DoingPostCheckpoint $doingPostCheckpoint = null;

    final public function setUserNotLoggedInCheckpoint(UserNotLoggedInCheckpoint $userNotLoggedInCheckpoint): void
    {
        $this->userNotLoggedInCheckpoint = $userNotLoggedInCheckpoint;
    }
    final protected function getUserNotLoggedInCheckpoint(): UserNotLoggedInCheckpoint
    {
        return $this->userNotLoggedInCheckpoint ??= $this->instanceManager->getInstance(UserNotLoggedInCheckpoint::class);
    }
    final public function setDoingPostCheckpoint(DoingPostCheckpoint $doingPostCheckpoint): void
    {
        $this->doingPostCheckpoint = $doingPostCheckpoint;
    }
    final protected function getDoingPostCheckpoint(): DoingPostCheckpoint
    {
        return $this->doingPostCheckpoint ??= $this->instanceManager->getInstance(DoingPostCheckpoint::class);
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
