<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\Checkpoint\CheckpointError;
use PoP\ComponentModel\FeedbackItemProviders\CheckpointErrorMessageProvider;
use PoP\Root\App;

class MutationCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const ENABLED_MUTATIONS = 'enabled-mutations';

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
            [self::class, self::ENABLED_MUTATIONS],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?CheckpointError
    {
        switch ($checkpoint[1]) {
            case self::ENABLED_MUTATIONS:
                if (!App::getState('are-mutations-enabled')) {
                    return new CheckpointError(
                        $this->getCheckpointErrorMessageProvider()->getMessage(CheckpointErrorMessageProvider::E1),
                        $this->getCheckpointErrorMessageProvider()->getNamespacedCode(CheckpointErrorMessageProvider::E1),
                    );
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}
