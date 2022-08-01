<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\Root\Services\StandaloneServiceTrait;
use PoP\GraphQLParser\Exception\Parser\InvalidDynamicContextException;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;

class DynamicVariableValuePromise implements DeferredValuePromiseInterface
{
    use StandaloneServiceTrait;

    public function __construct(
        public readonly DynamicVariableReference $dynamicVariableReference,
    ) {
    }

    public function resolveValue(): mixed
    {
        $dynamicVariables = App::getState('document-dynamic-variables');
        $variableName = $this->dynamicVariableReference->getName();
        if (!array_key_exists($variableName, $dynamicVariables)) {            
            // Variable is nowhere defined => Error
            throw new InvalidDynamicContextException(
                new FeedbackItemResolution(
                    GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                    GraphQLExtendedSpecErrorFeedbackItemProvider::E_5_8_3,
                    [
                        $this->dynamicVariableReference->getName(),
                    ]
                ),
                $this->dynamicVariableReference->getLocation(),
                $this->dynamicVariableReference
            );
        }
        return $dynamicVariables[$variableName];
    }
}
