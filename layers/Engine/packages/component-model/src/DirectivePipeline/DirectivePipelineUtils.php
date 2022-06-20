<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class DirectivePipelineUtils
{
    /**
     * @param array<array<string|int,EngineIterationFieldSet>> $pipelineIDFieldSet
     */
    public static function convertArgumentsToPayload(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $pipelineDirectiveResolvers,
        array $idObjects,
        array $unionDBKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$pipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        return [
            'typeResolver' => &$relationalTypeResolver,
            'pipelineDirectiveResolvers' => &$pipelineDirectiveResolvers,
            'idObjects' => &$idObjects,
            'unionDBKeyIDs' => &$unionDBKeyIDs,
            'previouslyResolvedIDFieldValues' => &$previouslyResolvedIDFieldValues,
            'pipelineIDFieldSet' => &$pipelineIDFieldSet,
            'resolvedIDFieldValues' => &$resolvedIDFieldValues,
            'variables' => &$variables,
            'messages' => &$messages,
            'engineIterationFeedbackStore' => &$engineIterationFeedbackStore,
        ];
    }

    public static function extractArgumentsFromPayload(array $payload): array
    {
        return [
            &$payload['typeResolver'],
            &$payload['pipelineDirectiveResolvers'],
            &$payload['idObjects'],
            &$payload['unionDBKeyIDs'],
            &$payload['previouslyResolvedIDFieldValues'],
            &$payload['pipelineIDFieldSet'],
            &$payload['resolvedIDFieldValues'],
            &$payload['variables'],
            &$payload['messages'],
            &$payload['engineIterationFeedbackStore'],
        ];
    }
}
