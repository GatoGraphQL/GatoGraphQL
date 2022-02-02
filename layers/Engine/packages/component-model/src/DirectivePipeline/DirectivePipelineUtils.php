<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

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
            'pipelineDirectiveResolverInstances' => &$pipelineDirectiveResolverInstances,
            'objectIDItems' => &$objectIDItems,
            'unionDBKeyIDs' => &$unionDBKeyIDs,
            'previousDBItems' => &$previousDBItems,
            'pipelineIDsDataFields' => &$pipelineIDsDataFields,
            'dbItems' => &$dbItems,
            'variables' => &$variables,
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
            &$payload['pipelineDirectiveResolverInstances'],
            &$payload['objectIDItems'],
            &$payload['unionDBKeyIDs'],
            &$payload['previousDBItems'],
            &$payload['pipelineIDsDataFields'],
            &$payload['dbItems'],
            &$payload['variables'],
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
