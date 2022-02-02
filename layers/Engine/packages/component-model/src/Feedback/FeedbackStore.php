<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class FeedbackStore
{
    private QueryFeedbackStore $queryFeedbackStore;
    private SchemaFeedbackStore $schemaFeedbackStore;
    private ObjectFeedbackStore $objectFeedbackStore;

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

    public function getQueryFeedbackStore(): QueryFeedbackStore
    {
        return $this->queryFeedbackStore;
    }

    public function getSchemaFeedbackStore(): SchemaFeedbackStore
    {
        return $this->schemaFeedbackStore;
    }

    public function getObjectFeedbackStore(): ObjectFeedbackStore
    {
        return $this->objectFeedbackStore;
    }
}
