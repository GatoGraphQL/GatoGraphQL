<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class EngineIterationFeedbackStore
{
    public SchemaFeedbackStore $schemaFeedbackStore;
    public ObjectFeedbackStore $objectFeedbackStore;

    public function __construct()
    {
        $this->schemaFeedbackStore = new SchemaFeedbackStore();
        $this->objectFeedbackStore = new ObjectFeedbackStore();
    }

    public function incorporate(
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
        string|int $objectID,
    ): void {
        $this->objectFeedbackStore->incorporate(
            $objectTypeFieldResolutionFeedbackStore,
            $relationalTypeResolver,
            $field,
            $objectID,
        );
    }
}
