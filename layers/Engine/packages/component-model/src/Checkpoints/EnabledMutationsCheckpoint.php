<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Checkpoints;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\FeedbackItemProviders\CheckpointErrorFeedbackItemProvider;
use PoP\Root\App;

class EnabledMutationsCheckpoint extends AbstractCheckpoint
{
    private ?CheckpointErrorFeedbackItemProvider $checkpointErrorFeedbackItemProvider = null;

    final public function setCheckpointErrorFeedbackItemProvider(CheckpointErrorFeedbackItemProvider $checkpointErrorFeedbackItemProvider): void
    {
        $this->checkpointErrorFeedbackItemProvider = $checkpointErrorFeedbackItemProvider;
    }
    final protected function getCheckpointErrorFeedbackItemProvider(): CheckpointErrorFeedbackItemProvider
    {
        /** @var CheckpointErrorFeedbackItemProvider */
        return $this->checkpointErrorFeedbackItemProvider ??= $this->instanceManager->getInstance(CheckpointErrorFeedbackItemProvider::class);
    }

    public function validateCheckpoint(): ?FeedbackItemResolution
    {
        if (!App::getState('are-mutations-enabled')) {
            return new FeedbackItemResolution(
                CheckpointErrorFeedbackItemProvider::class,
                CheckpointErrorFeedbackItemProvider::E1
            );
        }

        return parent::validateCheckpoint();
    }
}
