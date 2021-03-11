<?php

declare(strict_types=1);

namespace PoP\TraceTools\DirectiveResolvers;

use PoP\ComponentModel\Feedback\Tokens;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\TraceTools\ComponentConfiguration;
use PoP\TraceTools\Feedback\Tokens as TraceTokens;

/**
 * Trace the execution time of the resolution of the field(s), and log the result.
 * This directive is executed at the very end, to make sure that all involved directives are accounted for,
 * and it works together with "@startTraceExecutionTime" which is executed at the very beginning
 */
class EndTraceExecutionTimeDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    use TraceDirectiveResolverTrait;

    public const MESSAGE_EXECUTION_TIME = __CLASS__ . '-message-execution-time';

    /**
     * It's called "traceExecutionTime" instead of "endTraceExecutionTime" because it's more user-friendly,
     * and because "traceExecutionTime" involves both "startTraceExecutionTime" and "endTraceExecutionTime",
     * where "startTraceExecutionTime" is added as a mandatory directive on directive
     */
    public function getDirectiveName(): string
    {
        return 'traceExecutionTime';
    }

    /**
     * Place it after the validation and before it's added to $dbItems in the resolveAndMerge directive
     *
     * @return void
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::END;
    }

    /**
     * Save all the field values into the cache
     *
     * @param TypeResolverInterface $typeResolver
     * @param array $idsDataFields
     * @param array $succeedingPipelineIDsDataFields
     * @param array $resultIDItems
     * @param array $unionDBKeyIDs
     * @param array $dbItems
     * @param array $previousDBItems
     * @param array $variables
     * @param array $messages
     * @param array $dbErrors
     * @param array $dbWarnings
     * @param array $dbDeprecations
     * @param array $schemaErrors
     * @param array $schemaWarnings
     * @param array $schemaDeprecations
     * @return void
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
        $translationAPI = TranslationAPIFacade::getInstance();
        // Get the time set by @startTraceExecutionTime
        $startTime = $messages[EndTraceExecutionTimeDirectiveResolver::MESSAGE_EXECUTION_TIME];
        if (is_null($startTime)) {
            $schemaWarnings[] = [
                Tokens::PATH => [$this->directive],
                Tokens::MESSAGE => sprintf(
                    $translationAPI->__('Something went wrong when executing directive \'%s\'', 'trace-tools'),
                    $this->getDirectiveName()
                ),
            ];
            return;
        }
        $endTime = \hrtime(true);
        // Calculate the elapsed time (in nanoseconds)
        $executionTime = $endTime - $startTime;

        // Add the traces
        $schemaTraces[] = [
            Tokens::PATH => [$this->directive],
            Tokens::NAME => $this->getDirectiveName(),
            Tokens::EXTENSIONS => [
                Tokens::ID_FIELDS => array_map(
                    function ($dataFields) {
                        return $dataFields['direct'];
                    },
                    $idsDataFields
                ),
                TraceTokens::EXECUTION_TIME => $executionTime,
                TraceTokens::TIME_UNIT => 'nanoseconds',
            ]
        ];
        // Log the trace, if enabled
        if (ComponentConfiguration::sendTracesToLog()) {
            $feedbackMessageStore = FeedbackMessageStoreFacade::getInstance();
            $traceIDFields = [];
            foreach ($idsDataFields as $id => $dataFields) {
                $traceIDFields[] = sprintf(
                    $translationAPI->__('%s/%s', 'engine'),
                    $id,
                    implode($translationAPI->__('|'), $dataFields['direct'])
                );
            }
            $traceMessage = sprintf(
                $translationAPI->__('Execution time: %s milliseconds [type: \'%s\'; affected IDs/field(s): %s]', 'trace-tools'),
                $executionTime / 1_000_000, // Convert from nanoseconds to milliseconds
                $typeResolver->getTypeName(),
                implode($translationAPI->__(', '), $traceIDFields)
            );
            $feedbackMessageStore->addLogEntry($traceMessage);
        }
    }
    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Trace the execution time of the resolution of the fields, and log the result', 'trace-tools');
    }
}
