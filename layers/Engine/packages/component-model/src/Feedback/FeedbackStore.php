<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class FeedbackStore
{
    public GeneralFeedbackStore $generalFeedbackStore;
    public DocumentFeedbackStore $documentFeedbackStore;
    public SchemaFeedbackStore $schemaFeedbackStore;
    public ObjectFeedbackStore $objectFeedbackStore;

    public function __construct()
    {
        $this->regenerateGeneralFeedbackStore();
        $this->regenerateDocumentFeedbackStore();
    }

    public function regenerateGeneralFeedbackStore(): void
    {
        $this->generalFeedbackStore = new GeneralFeedbackStore();
    }

    public function regenerateDocumentFeedbackStore(): void
    {
        $this->documentFeedbackStore = new DocumentFeedbackStore();
    }

    public function regenerateSchemaFeedbackStore(): void
    {
        $this->schemaFeedbackStore = new SchemaFeedbackStore();
    }

    public function regenerateObjectFeedbackStore(): void
    {
        $this->objectFeedbackStore = new ObjectFeedbackStore();
    }
}
