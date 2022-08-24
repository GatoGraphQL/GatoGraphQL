<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\Checkpoints;

use PoP\ComponentModel\Checkpoints\AbstractAggregateCheckpoint;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\Engine\Checkpoints\DoingPostCheckpoint;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;

class DoingPostUserLoggedInAggregateCheckpoint extends AbstractAggregateCheckpoint
{
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?DoingPostCheckpoint $doingPostCheckpoint = null;

    final public function setUserLoggedInCheckpoint(UserLoggedInCheckpoint $userLoggedInCheckpoint): void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        /** @var UserLoggedInCheckpoint */
        return $this->userLoggedInCheckpoint ??= $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
    }
    final public function setDoingPostCheckpoint(DoingPostCheckpoint $doingPostCheckpoint): void
    {
        $this->doingPostCheckpoint = $doingPostCheckpoint;
    }
    final protected function getDoingPostCheckpoint(): DoingPostCheckpoint
    {
        /** @var DoingPostCheckpoint */
        return $this->doingPostCheckpoint ??= $this->instanceManager->getInstance(DoingPostCheckpoint::class);
    }

    /**
     * @return CheckpointInterface[]
     */
    protected function getCheckpoints(): array
    {
        return [
            $this->getDoingPostCheckpoint(),
            $this->getUserLoggedInCheckpoint(),
        ];
    }
}
