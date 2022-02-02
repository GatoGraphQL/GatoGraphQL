<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class DirectivePipelineUtils
{
    public static function convertArgumentsToPayload(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$pipelineIDsDataFields,
        array &$pipelineDirectiveResolverInstances,
        array $objectIDItems,
        array &$unionDBKeyIDs,
        array $previousDBItems,
        array &$variables,
        array &$dbItems,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$objectTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array {
        return [
            'typeResolver' => &$relationalTypeResolver,
            'pipelineIDsDataFields' => &$pipelineIDsDataFields,
            'pipelineDirectiveResolverInstances' => &$pipelineDirectiveResolverInstances,
            'objectIDItems' => &$objectIDItems,
            'unionDBKeyIDs' => &$unionDBKeyIDs,
            'previousDBItems' => &$previousDBItems,
            'variables' => &$variables,
            'dbItems' => &$dbItems,
            'messages' => &$messages,
            'objectErrors' => &$objectErrors,
            'objectWarnings' => &$objectWarnings,
            'objectDeprecations' => &$objectDeprecations,
            'objectNotices' => &$objectNotices,
            'objectTraces' => &$objectTraces,
            'schemaErrors' => &$schemaErrors,
            'schemaWarnings' => &$schemaWarnings,
            'schemaDeprecations' => &$schemaDeprecations,
            'schemaNotices' => &$schemaNotices,
            'schemaTraces' => &$schemaTraces,
        ];
    }

    public static function extractArgumentsFromPayload(array $payload): array
    {
        return [
            &$payload['typeResolver'],
            &$payload['pipelineIDsDataFields'],
            &$payload['pipelineDirectiveResolverInstances'],
            &$payload['objectIDItems'],
            &$payload['unionDBKeyIDs'],
            &$payload['previousDBItems'],
            &$payload['variables'],
            &$payload['dbItems'],
            &$payload['messages'],
            &$payload['objectErrors'],
            &$payload['objectWarnings'],
            &$payload['objectDeprecations'],
            &$payload['objectNotices'],
            &$payload['objectTraces'],
            &$payload['schemaErrors'],
            &$payload['schemaWarnings'],
            &$payload['schemaDeprecations'],
            &$payload['schemaNotices'],
            &$payload['schemaTraces'],
        ];
    }
}
