<?php
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;

class PoPCore_Dataload_CheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_PROFILEACCESS = 'checkpoint-profileaccess';
    public const CHECKPOINT_PROFILEACCESS_SUBMIT = 'checkpoint-profileaccess-submit';

    public function getCheckpointsToProcess()
    {
        return array(
            [self::class, self::CHECKPOINT_PROFILEACCESS],
            [self::class, self::CHECKPOINT_PROFILEACCESS_SUBMIT],
        );
    }

    public function process(array $checkpoint)
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_PROFILEACCESS:
                // Check if the user has Profile Access: access to add/edit content
                if (!userHasProfileAccess()) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('usernoprofileaccess');
                }
                break;

            case self::CHECKPOINT_PROFILEACCESS_SUBMIT:
                // Check if the user has Profile Access: access to add/edit content
                if (!doingPost()) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('notdoingpost');
                }

                if (!userHasProfileAccess()) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('usernoprofileaccess');
                }
                break;
        }

        return parent::process($checkpoint);
    }
}

