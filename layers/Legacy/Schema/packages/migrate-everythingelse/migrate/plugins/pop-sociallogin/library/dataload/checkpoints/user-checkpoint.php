<?php
use PoP\ComponentModel\Checkpoints\AbstractCheckpoint;
use PoP\Root\Feedback\FeedbackItemResolution;

class GD_WSL_Dataload_UserCheckpoint extends AbstractCheckpoint
{
    public final const CHECKPOINT_NONSOCIALLOGINUSER = 'wsl-checkpoint-nonsocialloginuser';

    /**
     * @todo Migrate to not using $checkpoint
     */
    public function validateCheckpoint(): ?FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_NONSOCIALLOGINUSER:
                if (isSocialloginUser()) {
                    return new FeedbackItemResolution('sociallogin-user', 'sociallogin-user');
                }
                break;
        }

        return parent::validateCheckpoint();
    }
}

