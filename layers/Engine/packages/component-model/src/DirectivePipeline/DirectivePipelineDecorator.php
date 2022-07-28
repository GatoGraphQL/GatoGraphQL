<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use League\Pipeline\PipelineInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

class DirectivePipelineDecorator
{
    public function __construct(
        private readonly PipelineInterface $pipeline
    ) {
    }

    /**
     * @param array<array<string|int,EngineIterationFieldSet>> $pipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $pipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function resolveDirectivePipeline(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$pipelineIDFieldSet,
        array &$pipelineFieldDataAccessProviders,
        array $pipelineDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $payload = $this->pipeline->__invoke(
            DirectivePipelineUtils::convertArgumentsToPayload(
                $relationalTypeResolver,
                $pipelineDirectiveResolvers,
                $idObjects,
                $unionTypeOutputKeyIDs,
                $previouslyResolvedIDFieldValues,
                $pipelineIDFieldSet,
                $pipelineFieldDataAccessProviders,
                $resolvedIDFieldValues,
                $variables,
                $messages,
                $engineIterationFeedbackStore,
            )
        );
        list(
            $relationalTypeResolver,
            $pipelineDirectiveResolvers,
            $idObjects,
            $unionTypeOutputKeyIDs,
            $previouslyResolvedIDFieldValues,
            /** @var array<array<string|int,EngineIterationFieldSet>> */
            $pipelineIDFieldSet,
            /** @var array<FieldDataAccessProviderInterface> */
            $pipelineFieldDataAccessProviders,
            /** @var array<string|int,SplObjectStorage<FieldInterface,mixed>> */
            $resolvedIDFieldValues,
            $variables,
            $messages,
            $engineIterationFeedbackStore,
        ) = DirectivePipelineUtils::extractArgumentsFromPayload($payload);
    }
}
