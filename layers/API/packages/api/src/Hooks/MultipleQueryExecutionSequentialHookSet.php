<?php

declare(strict_types=1);

namespace PoPAPI\API\Hooks;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Engine\EngineHookNames;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocumentInterface;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\Hooks\AbstractHookSet;

/**
 * Provide the per-operation iteration list to the engine when the
 * "Sequential Pass" Multiple Query Execution strategy is enabled.
 *
 * The component-model engine doesn't know about GraphQL operations; it
 * just iterates whatever opaque values this filter returns. We pull the
 * operation list from the executable document (which has already
 * topologically sorted them via `@depends`).
 */
class MultipleQueryExecutionSequentialHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            EngineHookNames::MULTIPLE_QUERY_EXECUTION_SEQUENTIAL_OPERATIONS,
            $this->maybeProvideSequentialOperations(...),
            10,
            1
        );
    }

    /**
     * Returns the list of operations to drain sequentially, or `$current`
     * (typically null) when the strategy is not active.
     *
     * @param mixed[]|null $current
     * @return mixed[]|null
     */
    public function maybeProvideSequentialOperations(?array $current): ?array
    {
        /** @var GraphQLParserModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLParserModule::class)->getConfiguration();
        if (
            !$moduleConfiguration->enableMultipleQueryExecution()
            || !$moduleConfiguration->enableSequentialMultipleQueryExecution()
        ) {
            return $current;
        }

        /** @var ExecutableDocumentInterface|null */
        $executableDocument = App::getState('executable-document-ast');
        if ($executableDocument === null) {
            return $current;
        }

        /** @var OperationInterface[]|null */
        $operations = $executableDocument->getMultipleOperationsToExecute();
        if ($operations === null || count($operations) <= 1) {
            // With 0 or 1 operations there's nothing to interleave;
            // let the engine take its single-pass code path.
            return $current;
        }

        return $operations;
    }
}
