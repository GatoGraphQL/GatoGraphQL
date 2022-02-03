<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class FeedbackStore
{
    public QueryFeedbackStore $queryFeedbackStore;
    public SchemaFeedbackStore $schemaFeedbackStore;
    public ObjectFeedbackStore $objectFeedbackStore;

    public function __construct()
    {
        $this->regenerateQueryFeedbackStore();
    }

    public function regenerateQueryFeedbackStore(): void
    {
        $this->queryFeedbackStore = new QueryFeedbackStore();
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
