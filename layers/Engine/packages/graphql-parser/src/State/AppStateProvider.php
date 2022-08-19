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
    }
}
