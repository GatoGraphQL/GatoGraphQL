<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\Checkpoints;

use PoP\ComponentModel\Checkpoints\AbstractCheckpoint;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\App;
use PoPCMSSchema\UserState\FeedbackItemProviders\CheckpointErrorFeedbackItemProvider;

class UserLoggedInCheckpoint extends AbstractCheckpoint
{
    public function validateCheckpoint(): ?FeedbackItemResolution
    {
        if (!App::getState('is-user-logged-in')) {
            return new FeedbackItemResolution(
                CheckpointErrorFeedbackItemProvider::class,
                CheckpointErrorFeedbackItemProvider::E1
            );
        }

        return parent::validateCheckpoint();
    }
}
