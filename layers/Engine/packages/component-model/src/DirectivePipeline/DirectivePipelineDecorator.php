<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
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
     * @param array<DirectiveResolverInterface> $pipelineFieldDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public function resolveDirectivePipeline(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $pipelineIDFieldSet,
        array $pipelineFieldDataAccessProviders,
        array $pipelineFieldDirectiveResolvers,
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
                $pipelineFieldDirectiveResolvers,
                $idObjects,
                $unionTypeOutputKeyIDs,
                $previouslyResolvedIDFieldValues,
                $pipelineIDFieldSet,
                $pipelineFieldDataAccessProviders,
                $resolvedIDFieldValues,
                $messages,
                $engineIterationFeedbackStore,
            )
        );
        list(
            $relationalTypeResolver,
            $pipelineFieldDirectiveResolvers,
            $idObjects,
            $unionTypeOutputKeyIDs,
            $previouslyResolvedIDFieldValues,
            /** @var array<array<string|int,EngineIterationFieldSet>> */
            $pipelineIDFieldSet,
            /** @var array<FieldDataAccessProviderInterface> */
            $pipelineFieldDataAccessProviders,
            /** @var array<string|int,SplObjectStorage<FieldInterface,mixed>> */
            $resolvedIDFieldValues,
            $messages,
            $engineIterationFeedbackStore,
        ) = DirectivePipelineUtils::extractArgumentsFromPayload($payload);
    }
}
