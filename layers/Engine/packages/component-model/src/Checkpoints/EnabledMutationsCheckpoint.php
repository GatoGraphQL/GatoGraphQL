<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Checkpoints;

use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\FeedbackItemProviders\CheckpointErrorFeedbackItemProvider;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;

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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableMutations()) {
            return new FeedbackItemResolution(
                CheckpointErrorFeedbackItemProvider::class,
                CheckpointErrorFeedbackItemProvider::E1
            );
        }

        return parent::validateCheckpoint();
    }
}
