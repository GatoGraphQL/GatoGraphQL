<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class EngineIterationFeedbackStore
{
    public SchemaFeedbackStore $schemaFeedbackStore;
    public ObjectFeedbackStore $objectFeedbackStore;

    public function __construct()
    {
        $this->schemaFeedbackStore = new SchemaFeedbackStore();
        $this->objectFeedbackStore = new ObjectFeedbackStore();
    }
}
