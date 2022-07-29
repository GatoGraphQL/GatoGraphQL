<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\State;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\State\AbstractAppStateProvider;
use SplObjectStorage;

class AppStateProvider extends AbstractAppStateProvider
{
    public function initialize(array &$state): void
    {
        /**
         * Dynamic variables are those generated on runtime
         * when resolving the GraphQL query, eg: via @export
         */
        $state['document-dynamic-variables'] = [];

        /**
         * After resolving the Field, store its value in
         * the AppState to resolve FieldValuePromises
         *
         * @var SplObjectStorage<FieldInterface,mixed>
         */
        $resolvedFieldValues = new SplObjectStorage();
        $state['engine-iteration-object-resolved-field-values'] = $resolvedFieldValues;
    }
}
