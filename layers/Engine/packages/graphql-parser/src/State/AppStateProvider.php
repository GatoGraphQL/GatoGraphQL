<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\State;

use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        /**
         * Dynamic variables are those generated on runtime
         * when resolving the GraphQL query, eg: via @export
         *
         * @var array<string,mixed>
         */
        $documentDynamicVariables = [];
        $state['document-dynamic-variables'] = $documentDynamicVariables;

        /**
         * The current operation being processed under the "Sequential Pass"
         * Multiple Query Execution strategy, or `null` outside of that loop.
         * Set by the engine inside its per-operation drain loop and read by
         * the API/GraphQL component processor to scope field collection to
         * that operation only. Initialized to `null` so the default
         * single-pass execution is unaffected.
         */
        $state['multiple-query-execution-current-operation'] = null;
    }
}
