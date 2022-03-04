<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class DirectivePipelineUtils
{
    public static function convertArgumentsToPayload(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $pipelineDirectiveResolverInstances,
        array $objectIDItems,
        array $unionDBKeyIDs,
        array $previousDBItems,
        array &$pipelineIDsDataFields,
        array &$dbItems,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        return [
            'typeResolver' => &$relationalTypeResolver,
            'pipelineDirectiveResolverInstances' => &$pipelineDirectiveResolverInstances,
            'objectIDItems' => &$objectIDItems,
            'unionDBKeyIDs' => &$unionDBKeyIDs,
            'previousDBItems' => &$previousDBItems,
            'pipelineIDsDataFields' => &$pipelineIDsDataFields,
            'dbItems' => &$dbItems,
            'variables' => &$variables,
            'messages' => &$messages,
            'engineIterationFeedbackStore' => &$engineIterationFeedbackStore,
        ];
    }

    public static function extractArgumentsFromPayload(array $payload): array
    {
        return [
            &$payload['typeResolver'],
            &$payload['pipelineDirectiveResolverInstances'],
            &$payload['objectIDItems'],
            &$payload['unionDBKeyIDs'],
            &$payload['previousDBItems'],
            &$payload['pipelineIDsDataFields'],
            &$payload['dbItems'],
            &$payload['variables'],
            &$payload['messages'],
            &$payload['engineIterationFeedbackStore'],
        ];
    }
}
