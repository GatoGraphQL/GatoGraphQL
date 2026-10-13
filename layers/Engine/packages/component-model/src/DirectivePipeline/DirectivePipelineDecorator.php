<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use League\Pipeline\PipelineInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
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
     * @param array<FieldDirectiveResolverInterface> $pipelineFieldDirectiveResolvers
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
        $appStateManager = App::getAppStateManager();
        /** @var int */
        $directivePipelineDepth = App::getState('directive-pipeline-depth');
        $appStateManager->override('directive-pipeline-depth', $directivePipelineDepth + 1);
        /** @var DirectiveNullabilityValidationServiceInterface */
        $directiveNullabilityValidationService = InstanceManagerFacade::getInstance()->getInstance(DirectiveNullabilityValidationServiceInterface::class);
        $mustValidateDirectivePipeline = $directiveNullabilityValidationService->mustValidateDirectivePipeline();
        if ($mustValidateDirectivePipeline) {
            $directiveNullabilityValidationService->startDirectivePipeline($engineIterationFeedbackStore);
        }
        try {
            $this->doResolveDirectivePipeline(
                $relationalTypeResolver,
                $pipelineIDFieldSet,
                $pipelineFieldDataAccessProviders,
                $pipelineFieldDirectiveResolvers,
                $idObjects,
                $unionTypeOutputKeyIDs,
                $previouslyResolvedIDFieldValues,
                $resolvedIDFieldValues,
                $messages,
                $engineIterationFeedbackStore,
            );
        } finally {
            if ($mustValidateDirectivePipeline) {
                $directiveNullabilityValidationService->endDirectivePipeline(
                    $relationalTypeResolver,
                    $idObjects,
                    $resolvedIDFieldValues,
                    $engineIterationFeedbackStore,
                );
            }
            $appStateManager->override('directive-pipeline-depth', $directivePipelineDepth);
        }
    }

    /**
     * @param array<array<string|int,EngineIterationFieldSet>> $pipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $pipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<FieldDirectiveResolverInterface> $pipelineFieldDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    protected function doResolveDirectivePipeline(
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
