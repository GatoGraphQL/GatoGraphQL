<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\State;

use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    public function initialize(array &$state): void
    {
        /**
         * Dynamic variables are those generated on runtime
         * when resolving the GraphQL query, eg: via @export
         */
        $state['document-dynamic-variables'] = [];
    }
}
