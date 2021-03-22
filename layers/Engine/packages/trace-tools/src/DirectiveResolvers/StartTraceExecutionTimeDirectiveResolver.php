<?php

declare(strict_types=1);

namespace PoP\TraceTools\DirectiveResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;

/**
 * Start measuring the time of resolution of the field(s).
 * This directive is executed at the very beginning, and it works together with "@endTraceExecutionTime"
 * (called @traceExecutionTime) which is executed at the very end.
 */
class StartTraceExecutionTimeDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    use TraceDirectiveResolverTrait;

    public function getDirectiveName(): string
    {
        return 'startTraceExecutionTime';
    }

    /**
     * Place it after the validation and before it's added to $dbItems in the resolveAndMerge directive
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::BEGINNING;
    }

    /**
     * This directive is added automatically by @traceExecutionTime, it's not added by the user
     */
    public function skipAddingToSchemaDefinition(): bool
    {
        return true;
    }

    /**
     * Save all the field values into the cache
     */
    public function resolveDirective(
        TypeResolverInterface $typeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
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
        // Get the current time, and communicate it to @traceExecutionTime through a message
        $messages[EndTraceExecutionTimeDirectiveResolver::MESSAGE_EXECUTION_TIME] = \hrtime(true);
    }
    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Start measuring the time for the execution of the field(s)', 'trace-tools');
    }
}
