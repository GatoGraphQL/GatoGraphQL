<?php

declare(strict_types=1);

namespace PoPSchema\UserState\CheckpointProcessors;

use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\ErrorHandling\Error;

class UserStateCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const USERLOGGEDIN = 'userloggedin';
    public const USERNOTLOGGEDIN = 'usernotloggedin';

    public function getCheckpointsToProcess()
    {
        return array(
            [self::class, self::USERLOGGEDIN],
            [self::class, self::USERNOTLOGGEDIN],
        );
    }

    public function process(array $checkpoint)
    {
        $vars = ApplicationState::getVars();
        switch ($checkpoint[1]) {
            case self::USERLOGGEDIN:
                if (!$vars['global-userstate']['is-user-logged-in']) {
                    return new Error('usernotloggedin');
                }
                break;

            case self::USERNOTLOGGEDIN:
                if ($vars['global-userstate']['is-user-logged-in']) {
                    return new Error('userloggedin');
                }
                break;
        }

        return parent::process($checkpoint);
    }
}
