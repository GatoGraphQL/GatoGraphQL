<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\CheckpointProcessors;

use PoP\ComponentModel\Checkpoint\CheckpointError;
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\Root\App;
use PoPCMSSchema\UserState\FeedbackItemProviders\CheckpointErrorMessageProvider;

class UserStateCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const USERLOGGEDIN = 'userloggedin';
    public const USERNOTLOGGEDIN = 'usernotloggedin';

    private ?CheckpointErrorMessageProvider $checkpointErrorMessageProvider = null;

    final public function setCheckpointErrorMessageProvider(CheckpointErrorMessageProvider $checkpointErrorMessageProvider): void
    {
        $this->checkpointErrorMessageProvider = $checkpointErrorMessageProvider;
    }
    final protected function getCheckpointErrorMessageProvider(): CheckpointErrorMessageProvider
    {
        return $this->checkpointErrorMessageProvider ??= $this->instanceManager->getInstance(CheckpointErrorMessageProvider::class);
    }

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::USERLOGGEDIN],
            [self::class, self::USERNOTLOGGEDIN],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?CheckpointError
    {
        switch ($checkpoint[1]) {
            case self::USERLOGGEDIN:
                if (!App::getState('is-user-logged-in')) {
                    return new CheckpointError(
                        $this->getCheckpointErrorMessageProvider()->getMessage(CheckpointErrorMessageProvider::E1),
                        $this->getCheckpointErrorMessageProvider()->getNamespacedCode(CheckpointErrorMessageProvider::E1),
                    );
                }
                break;

            case self::USERNOTLOGGEDIN:
                if (App::getState('is-user-logged-in')) {
                    return new CheckpointError(
                        $this->getCheckpointErrorMessageProvider()->getMessage(CheckpointErrorMessageProvider::E2),
                        $this->getCheckpointErrorMessageProvider()->getNamespacedCode(CheckpointErrorMessageProvider::E2),
                    );
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}
