<?php

declare(strict_types=1);

namespace PoP\Engine\CheckpointProcessors;

use PoP\ComponentModel\Checkpoint\CheckpointError;
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\Engine\FeedbackMessageProviders\CheckpointErrorMessageProvider;
use PoP\Root\App;

class RequestCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const DOING_POST = 'doing-post';

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
            [self::class, self::DOING_POST],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?CheckpointError
    {
        switch ($checkpoint[1]) {
            case self::DOING_POST:
                if ('POST' !== App::server('REQUEST_METHOD')) {
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
