<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use League\Pipeline\PipelineInterface;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class DirectivePipelineDecorator
{
    public function __construct(private PipelineInterface $pipeline)
    {
    }

    public function resolveDirectivePipeline(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$pipelineIDsDataFields,
        array $pipelineDirectiveResolverInstances,
        array $objectIDItems,
        array $unionDBKeyIDs,
        array $previousDBItems,
        array &$dbItems,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
    ): void {
        $payload = $this->pipeline->__invoke(
            DirectivePipelineUtils::convertArgumentsToPayload(
                $relationalTypeResolver,
                $pipelineDirectiveResolverInstances,
                $objectIDItems,
                $unionDBKeyIDs,
                $previousDBItems,
                $pipelineIDsDataFields,
                $dbItems,
                $variables,
                $messages,
                $engineIterationFeedbackStore,
                $objectErrors,
                $objectWarnings,
                $objectDeprecations,
                $objectNotices,
                $schemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
                $schemaNotices,
            )
        );
        list(
            $relationalTypeResolver,
            $pipelineDirectiveResolverInstances,
            $objectIDItems,
            $unionDBKeyIDs,
            $previousDBItems,
            $pipelineIDsDataFields,
            $dbItems,
            $variables,
            $messages,
            $engineIterationFeedbackStore,
            $objectErrors,
            $objectWarnings,
            $objectDeprecations,
            $objectNotices,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
            $schemaNotices,
        ) = DirectivePipelineUtils::extractArgumentsFromPayload($payload);
    }
}
