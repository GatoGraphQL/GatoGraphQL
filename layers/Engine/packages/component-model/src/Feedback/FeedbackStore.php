<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class FeedbackStore
{
    public GeneralFeedbackStore $generalFeedbackStore;
    public SchemaFeedbackStore $schemaFeedbackStore;

    public function __construct()
    {
        $this->regenerateGeneralFeedbackStore();
        $this->regenerateSchemaFeedbackStore();
    }

    public function regenerateGeneralFeedbackStore(): void
    {
        $this->generalFeedbackStore = new GeneralFeedbackStore();
    }

    public function regenerateSchemaFeedbackStore(): void
    {
        $this->schemaFeedbackStore = new SchemaFeedbackStore();
    }
}
