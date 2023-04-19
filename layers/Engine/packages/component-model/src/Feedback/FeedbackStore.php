<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class FeedbackStore
{
    public GeneralFeedbackStore $generalFeedbackStore;
    public DocumentFeedbackStore $documentFeedbackStore;

    public function __construct()
    {
        $this->generateGeneralFeedbackStore();
        $this->generateDocumentFeedbackStore();
    }

    public function generateGeneralFeedbackStore(): void
    {
        $this->generalFeedbackStore = new GeneralFeedbackStore();
    }

    public function generateDocumentFeedbackStore(): void
    {
        $this->documentFeedbackStore = new DocumentFeedbackStore();
    }
}
