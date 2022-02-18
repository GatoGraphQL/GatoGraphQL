<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\FeedbackItemProviders\CheckpointErrorFeedbackItemProvider;
use PoP\Root\App;

class MutationCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const ENABLED_MUTATIONS = 'enabled-mutations';

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
            [self::class, self::ENABLED_MUTATIONS],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case self::ENABLED_MUTATIONS:
                if (!App::getState('are-mutations-enabled')) {
                    return new FeedbackItemResolution(
                        CheckpointErrorFeedbackItemProvider::class,
                        CheckpointErrorFeedbackItemProvider::E1
                    );
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}
