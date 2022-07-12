<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class EngineIterationFeedbackStore
{
    public SchemaFeedbackStore $schemaFeedbackStore;
    public ObjectResolutionFeedbackStore $objectFeedbackStore;

    public function __construct()
    {
        $this->schemaFeedbackStore = new SchemaFeedbackStore();
        $this->objectFeedbackStore = new ObjectResolutionFeedbackStore();
    }

    public function incorporate(
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $this->schemaFeedbackStore->incorporate($engineIterationFeedbackStore->schemaFeedbackStore);
        $this->objectFeedbackStore->incorporate($engineIterationFeedbackStore->objectFeedbackStore);
    }

    public function hasErrors(): bool
    {
        return $this->schemaFeedbackStore->getErrors() !== []
            || $this->objectFeedbackStore->getErrors() !== [];
    }
}
