<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class EngineIterationFeedbackStore
{
    public SchemaFeedbackStore $schemaFeedbackStore;
    public ObjectResolutionFeedbackStore $objectResolutionFeedbackStore;

    public function __construct()
    {
        $this->schemaFeedbackStore = new SchemaFeedbackStore();
        $this->objectResolutionFeedbackStore = new ObjectResolutionFeedbackStore();
    }

    public function incorporate(
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $this->schemaFeedbackStore->incorporate($engineIterationFeedbackStore->schemaFeedbackStore);
        $this->objectResolutionFeedbackStore->incorporate($engineIterationFeedbackStore->objectResolutionFeedbackStore);
    }

    public function hasErrors(): bool
    {
        return $this->schemaFeedbackStore->getErrors() !== []
            || $this->objectResolutionFeedbackStore->getErrors() !== [];
    }

    public function getErrorCount(): int
    {
        return $this->schemaFeedbackStore->getErrorCount()
            + $this->objectResolutionFeedbackStore->getErrorCount();
    }
}
