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
        array &$resultIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
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
            'resultIDItems' => &$resultIDItems,
            'unionDBKeyIDs' => &$unionDBKeyIDs,
            'dbItems' => &$dbItems,
            'previousDBItems' => &$previousDBItems,
            'variables' => &$variables,
            'messages' => &$messages,
            'dbErrors' => &$dbErrors,
            'dbWarnings' => &$dbWarnings,
            'dbDeprecations' => &$dbDeprecations,
            'dbNotices' => &$dbNotices,
            'dbTraces' => &$dbTraces,
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
            &$payload['resultIDItems'],
            &$payload['unionDBKeyIDs'],
            &$payload['dbItems'],
            &$payload['previousDBItems'],
            &$payload['variables'],
            &$payload['messages'],
            &$payload['dbErrors'],
            &$payload['dbWarnings'],
            &$payload['dbDeprecations'],
            &$payload['dbNotices'],
            &$payload['dbTraces'],
            &$payload['schemaErrors'],
            &$payload['schemaWarnings'],
            &$payload['schemaDeprecations'],
            &$payload['schemaNotices'],
            &$payload['schemaTraces'],
        ];
    }
}
