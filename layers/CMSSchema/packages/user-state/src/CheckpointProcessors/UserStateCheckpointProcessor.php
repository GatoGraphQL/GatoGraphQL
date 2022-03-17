<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\CheckpointProcessors;

use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\App;
use PoPCMSSchema\UserState\FeedbackItemProviders\CheckpointErrorFeedbackItemProvider;

class UserStateCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const USERLOGGEDIN = 'userloggedin';
    public const USERNOTLOGGEDIN = 'usernotloggedin';

    private ?CheckpointErrorFeedbackItemProvider $checkpointErrorFeedbackItemProvider = null;

    final public function setCheckpointErrorFeedbackItemProvider(CheckpointErrorFeedbackItemProvider $checkpointErrorFeedbackItemProvider): void
    {
        $this->checkpointErrorFeedbackItemProvider = $checkpointErrorFeedbackItemProvider;
    }
    final protected function getCheckpointErrorFeedbackItemProvider(): CheckpointErrorFeedbackItemProvider
    {
        return $this->checkpointErrorFeedbackItemProvider ??= $this->instanceManager->getInstance(CheckpointErrorFeedbackItemProvider::class);
    }

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::USERLOGGEDIN],
            [self::class, self::USERNOTLOGGEDIN],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case self::USERLOGGEDIN:
                if (!App::getState('is-user-logged-in')) {
                    return new FeedbackItemResolution(
                        CheckpointErrorFeedbackItemProvider::class,
                        CheckpointErrorFeedbackItemProvider::E1
                    );
                }
                break;

            case self::USERNOTLOGGEDIN:
                if (App::getState('is-user-logged-in')) {
                    return new FeedbackItemResolution(
                        CheckpointErrorFeedbackItemProvider::class,
                        CheckpointErrorFeedbackItemProvider::E2
                    );
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}
