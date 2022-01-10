<?php

declare(strict_types=1);

namespace PoPSchema\UserState\CheckpointProcessors;

use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\State\ApplicationState;

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

    public function validateCheckpoint(array $checkpoint): ?Error
    {
        $vars = ApplicationState::getVars();
        switch ($checkpoint[1]) {
            case self::USERLOGGEDIN:
                if (!$vars['is-user-logged-in']) {
                    return new Error('usernotloggedin');
                }
                break;

            case self::USERNOTLOGGEDIN:
                if ($vars['is-user-logged-in']) {
                    return new Error('userloggedin');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}
