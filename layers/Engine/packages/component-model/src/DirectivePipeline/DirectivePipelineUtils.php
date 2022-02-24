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
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
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
            'objectErrors' => &$objectErrors,
            'objectWarnings' => &$objectWarnings,
            'objectDeprecations' => &$objectDeprecations,
            'objectNotices' => &$objectNotices,
            'schemaErrors' => &$schemaErrors,
            'schemaWarnings' => &$schemaWarnings,
            'schemaDeprecations' => &$schemaDeprecations,
            'schemaNotices' => &$schemaNotices,
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
            &$payload['objectErrors'],
            &$payload['objectWarnings'],
            &$payload['objectDeprecations'],
            &$payload['objectNotices'],
            &$payload['schemaErrors'],
            &$payload['schemaWarnings'],
            &$payload['schemaDeprecations'],
            &$payload['schemaNotices'],
        ];
    }
}
