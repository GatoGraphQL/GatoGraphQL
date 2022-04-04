<?php
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\Root\Feedback\FeedbackItemResolution;

class PoPCore_Dataload_CheckpointProcessor extends AbstractCheckpointProcessor
{
    public final const CHECKPOINT_PROFILEACCESS = 'checkpoint-profileaccess';
    public final const CHECKPOINT_PROFILEACCESS_SUBMIT = 'checkpoint-profileaccess-submit';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::CHECKPOINT_PROFILEACCESS],
            [self::class, self::CHECKPOINT_PROFILEACCESS_SUBMIT],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_PROFILEACCESS:
                // Check if the user has Profile Access: access to add/edit content
                if (!userHasProfileAccess()) {
                    return new FeedbackItemResolution('usernoprofileaccess', 'usernoprofileaccess');
                }
                break;

            case self::CHECKPOINT_PROFILEACCESS_SUBMIT:
                // Check if the user has Profile Access: access to add/edit content
                if (!doingPost()) {
                    return new FeedbackItemResolution('notdoingpost', 'notdoingpost');
                }

                if (!userHasProfileAccess()) {
                    return new FeedbackItemResolution('usernoprofileaccess', 'usernoprofileaccess');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}

