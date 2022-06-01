<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Checkpoints;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\FeedbackItemProviders\CheckpointErrorFeedbackItemProvider;
use PoP\Root\App;

class MutationCheckpoint extends AbstractCheckpoint
{
    public final const ENABLED_MUTATIONS = 'enabled-mutations';

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

    public function validateCheckpoint(): ?FeedbackItemResolution
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

        return parent::validateCheckpoint();
    }
}
