<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\CheckpointProcessors;

use PoP\Root\App;
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\ComponentModel\Error\Error;

class UserStateCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const USERLOGGEDIN = 'userloggedin';
    public const USERNOTLOGGEDIN = 'usernotloggedin';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::USERLOGGEDIN],
            [self::class, self::USERNOTLOGGEDIN],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?\PoP\ComponentModel\Checkpoint\CheckpointError
    {
        switch ($checkpoint[1]) {
            case self::USERLOGGEDIN:
                if (!App::getState('is-user-logged-in')) {
                    return new Error('usernotloggedin');
                }
                break;

            case self::USERNOTLOGGEDIN:
                if (App::getState('is-user-logged-in')) {
                    return new Error('userloggedin');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}
