<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use League\Pipeline\PipelineInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class DirectivePipelineDecorator
{
    public function __construct(
        private readonly PipelineInterface $pipeline
    ) {
    }

    /**
     * @param array<array<string|int,EngineIterationFieldSet>> $pipelineIDFieldSet
     */
    public function resolveDirectivePipeline(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$pipelineIDFieldSet,
        array $pipelineDirectiveResolvers,
        array $objectIDItems,
        array $unionDBKeyIDs,
        array $previousResolvedIDFieldValues,
        array &$resolvedIDFieldValues,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $payload = $this->pipeline->__invoke(
            DirectivePipelineUtils::convertArgumentsToPayload(
                $relationalTypeResolver,
                $pipelineDirectiveResolvers,
                $objectIDItems,
                $unionDBKeyIDs,
                $previousResolvedIDFieldValues,
                $pipelineIDFieldSet,
                $resolvedIDFieldValues,
                $variables,
                $messages,
                $engineIterationFeedbackStore,
            )
        );
        list(
            $relationalTypeResolver,
            $pipelineDirectiveResolvers,
            $objectIDItems,
            $unionDBKeyIDs,
            $previousResolvedIDFieldValues,
            /** @var array<array<string|int,EngineIterationFieldSet>> */
            $pipelineIDFieldSet,
            $resolvedIDFieldValues,
            $variables,
            $messages,
            $engineIterationFeedbackStore,
        ) = DirectivePipelineUtils::extractArgumentsFromPayload($payload);
    }
}
