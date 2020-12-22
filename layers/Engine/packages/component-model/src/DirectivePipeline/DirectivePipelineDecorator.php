<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use League\Pipeline\PipelineInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class DirectivePipelineDecorator
{
    private PipelineInterface $pipeline;

    public function __construct(PipelineInterface $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    public function resolveDirectivePipeline(
        TypeResolverInterface $typeResolver,
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
    ): void {
        $payload = $this->pipeline->process(
            DirectivePipelineUtils::convertArgumentsToPayload(
                $typeResolver,
                $pipelineIDsDataFields,
                $pipelineDirectiveResolverInstances,
                $resultIDItems,
                $unionDBKeyIDs,
                $dbItems,
                $previousDBItems,
                $variables,
                $messages,
                $dbErrors,
                $dbWarnings,
                $dbDeprecations,
                $dbNotices,
                $dbTraces,
                $schemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
                $schemaNotices,
                $schemaTraces
            )
        );
        list(
            $typeResolver,
            $pipelineIDsDataFields,
            $pipelineDirectiveResolverInstances,
            $resultIDItems,
            $unionDBKeyIDs,
            $dbItems,
            $previousDBItems,
            $variables,
            $messages,
            $dbErrors,
            $dbWarnings,
            $dbDeprecations,
            $dbNotices,
            $dbTraces,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
            $schemaNotices,
            $schemaTraces
        ) = DirectivePipelineUtils::extractArgumentsFromPayload($payload);
    }
}
