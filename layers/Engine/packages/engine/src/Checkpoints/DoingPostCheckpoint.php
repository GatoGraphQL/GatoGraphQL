<?php

declare(strict_types=1);

namespace PoP\Engine\Checkpoints;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Checkpoints\AbstractCheckpoint;
use PoP\Engine\FeedbackItemProviders\CheckpointErrorFeedbackItemProvider;
use PoP\Root\App;

class DoingPostCheckpoint extends AbstractCheckpoint
{
    public function validateCheckpoint(): ?FeedbackItemResolution
    {
        if ('POST' !== App::server('REQUEST_METHOD')) {
            return new FeedbackItemResolution(
                CheckpointErrorFeedbackItemProvider::class,
                CheckpointErrorFeedbackItemProvider::E1
            );
        }

        return parent::validateCheckpoint();
    }
}
