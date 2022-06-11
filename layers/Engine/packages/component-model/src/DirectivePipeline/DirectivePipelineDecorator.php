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
     * @param array<array<string|int,EngineIterationFieldSet>> $pipelineIDsDataFields
     */
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
            )
        );
        list(
            $relationalTypeResolver,
            $pipelineDirectiveResolverInstances,
            $objectIDItems,
            $unionDBKeyIDs,
            $previousDBItems,
            /** @var array<array<string|int,EngineIterationFieldSet>> */
            $pipelineIDsDataFields,
            $dbItems,
            $variables,
            $messages,
            $engineIterationFeedbackStore,
        ) = DirectivePipelineUtils::extractArgumentsFromPayload($payload);
    }
}
