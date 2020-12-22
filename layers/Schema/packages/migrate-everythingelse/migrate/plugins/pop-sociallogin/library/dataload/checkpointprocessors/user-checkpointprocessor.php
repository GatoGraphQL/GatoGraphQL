<?php
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;

class GD_WSL_Dataload_UserCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_NONSOCIALLOGINUSER = 'wsl-checkpoint-nonsocialloginuser';

    public function getCheckpointsToProcess()
    {
        return array(
            [self::class, self::CHECKPOINT_NONSOCIALLOGINUSER],
        );
    }

    public function process(array $checkpoint)
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_NONSOCIALLOGINUSER:
                if (isSocialloginUser()) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('sociallogin-user');
                }
                break;
        }

        return parent::process($checkpoint);
    }
}

