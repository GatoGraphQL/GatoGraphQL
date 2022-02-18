<?php
use PoP\ComponentModel\Checkpoint\CheckpointError;
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;

class PoPCore_Dataload_CheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_PROFILEACCESS = 'checkpoint-profileaccess';
    public const CHECKPOINT_PROFILEACCESS_SUBMIT = 'checkpoint-profileaccess-submit';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::CHECKPOINT_PROFILEACCESS],
            [self::class, self::CHECKPOINT_PROFILEACCESS_SUBMIT],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?\PoP\ComponentModel\Feedback\FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_PROFILEACCESS:
                // Check if the user has Profile Access: access to add/edit content
                if (!userHasProfileAccess()) {
                    return new CheckpointError('usernoprofileaccess', 'usernoprofileaccess');
                }
                break;

            case self::CHECKPOINT_PROFILEACCESS_SUBMIT:
                // Check if the user has Profile Access: access to add/edit content
                if (!doingPost()) {
                    return new CheckpointError('notdoingpost', 'notdoingpost');
                }

                if (!userHasProfileAccess()) {
                    return new CheckpointError('usernoprofileaccess', 'usernoprofileaccess');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}

