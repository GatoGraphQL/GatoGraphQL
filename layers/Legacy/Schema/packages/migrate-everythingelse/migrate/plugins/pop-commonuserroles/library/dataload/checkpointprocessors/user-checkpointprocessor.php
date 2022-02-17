<?php

use PoP\ComponentModel\Checkpoint\CheckpointError;
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;

class GD_URE_Dataload_UserCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_LOGGEDINUSER_ISPROFILEORGANIZATION = 'checkpoint-loggedinuser-isprofileorganization';
    public const CHECKPOINT_LOGGEDINUSER_ISPROFILEINDIVIDUAL = 'checkpoint-loggedinuser-isprofileindividual';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::CHECKPOINT_LOGGEDINUSER_ISPROFILEORGANIZATION],
            [self::class, self::CHECKPOINT_LOGGEDINUSER_ISPROFILEINDIVIDUAL],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?CheckpointError
    {
        $current_user_id = \PoP\Root\App::getState('current-user-id');
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_LOGGEDINUSER_ISPROFILEORGANIZATION:
                if (!gdUreIsOrganization($current_user_id)) {
                    return new CheckpointError('profilenotorganization', 'profilenotorganization');
                }
                break;

            case self::CHECKPOINT_LOGGEDINUSER_ISPROFILEINDIVIDUAL:
                if (!gdUreIsIndividual($current_user_id)) {
                    return new CheckpointError('profilenotindividual', 'profilenotindividual');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}

