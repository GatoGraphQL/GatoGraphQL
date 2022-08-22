<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;

interface FeedbackEntryManagerInterface
{
    /**
     * Add the feedback (errors, warnings, deprecations, notices, etc)
     * into the output.
     *
     * @param array<string,mixed> $data
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $schemaFeedbackEntries
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $objectFeedbackEntries
     * @return array<string,mixed>
     */
    public function combineAndAddFeedbackEntries(
        array $data,
        array $schemaFeedbackEntries,
        array $objectFeedbackEntries,
    ): array;

    /**
     * @param array<string,mixed> $extensions
     * @return array<string,mixed>
     */
    public function formatObjectOrSchemaFeedbackCommonEntry(
        AstInterface $astNode,
        Location $location,
        array $extensions,
        FeedbackItemResolution $feedbackItemResolution,
    ): array;
    
    /**
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $objectFeedbackEntries
     */
    public function transferObjectFeedback(
        array $idObjects,
        ObjectResolutionFeedbackStore $objectResolutionFeedbackStore,
        array &$objectFeedbackEntries,
    ): void;

    /**
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $schemaFeedbackEntries
     */
    public function transferSchemaFeedback(
        SchemaFeedbackStore $schemaFeedbackStore,
        array &$schemaFeedbackEntries,
    ): void;
}
