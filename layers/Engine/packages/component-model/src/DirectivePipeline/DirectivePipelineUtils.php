<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

class DirectivePipelineUtils
{
    /**
     * @param array<array<string|int,EngineIterationFieldSet>> $pipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $pipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @return array<string,mixed>
     * @param array<string, mixed> $pipelineDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public static function convertArgumentsToPayload(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $pipelineDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array $pipelineIDFieldSet,
        array $pipelineFieldDataAccessProviders,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        return [
            'typeResolver' => &$relationalTypeResolver,
            'pipelineDirectiveResolvers' => &$pipelineDirectiveResolvers,
            'idObjects' => &$idObjects,
            'unionTypeOutputKeyIDs' => &$unionTypeOutputKeyIDs,
            'previouslyResolvedIDFieldValues' => &$previouslyResolvedIDFieldValues,
            'pipelineIDFieldSet' => &$pipelineIDFieldSet,
            'pipelineFieldDataAccessProviders' => &$pipelineFieldDataAccessProviders,
            'resolvedIDFieldValues' => &$resolvedIDFieldValues,
            'messages' => &$messages,
            'engineIterationFeedbackStore' => &$engineIterationFeedbackStore,
        ];
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $payload
     */
    public static function extractArgumentsFromPayload(array $payload): array
    {
        return [
            &$payload['typeResolver'],
            &$payload['pipelineDirectiveResolvers'],
            &$payload['idObjects'],
            &$payload['unionTypeOutputKeyIDs'],
            &$payload['previouslyResolvedIDFieldValues'],
            &$payload['pipelineIDFieldSet'],
            &$payload['pipelineFieldDataAccessProviders'],
            &$payload['resolvedIDFieldValues'],
            &$payload['messages'],
            &$payload['engineIterationFeedbackStore'],
        ];
    }
}
