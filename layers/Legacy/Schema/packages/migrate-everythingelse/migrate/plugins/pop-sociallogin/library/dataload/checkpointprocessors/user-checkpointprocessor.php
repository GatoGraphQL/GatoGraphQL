<?php
use PoP\ComponentModel\Checkpoint\CheckpointError;
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;

class GD_WSL_Dataload_UserCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_NONSOCIALLOGINUSER = 'wsl-checkpoint-nonsocialloginuser';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::CHECKPOINT_NONSOCIALLOGINUSER],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?\PoP\ComponentModel\Feedback\FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_NONSOCIALLOGINUSER:
                if (isSocialloginUser()) {
                    return new CheckpointError('sociallogin-user', 'sociallogin-user');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}

