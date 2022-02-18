<?php

declare(strict_types=1);

namespace PoP\Engine\CheckpointProcessors;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\Engine\FeedbackItemProviders\CheckpointErrorFeedbackItemProvider;
use PoP\Root\App;

class RequestCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const DOING_POST = 'doing-post';

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
            [self::class, self::DOING_POST],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case self::DOING_POST:
                if ('POST' !== App::server('REQUEST_METHOD')) {
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
