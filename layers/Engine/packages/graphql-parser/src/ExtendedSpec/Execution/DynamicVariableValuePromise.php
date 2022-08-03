<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\Root\Services\StandaloneServiceTrait;
use PoP\GraphQLParser\Exception\Parser\InvalidRuntimeVariableReferenceException;
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

    /**
     * @throws InvalidRuntimeVariableReferenceException When accessing non-declared Dynamic Variables
     */
    public function resolveValue(): mixed
    {
        $dynamicVariables = App::getState('document-dynamic-variables');
        $variableName = $this->dynamicVariableReference->getName();
        if (!array_key_exists($variableName, $dynamicVariables)) {
            // Variable is nowhere defined => Error
            throw new InvalidRuntimeVariableReferenceException(
                new FeedbackItemResolution(
                    GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                    GraphQLExtendedSpecErrorFeedbackItemProvider::E_5_8_3,
                    [
                        $this->dynamicVariableReference->getName(),
                    ]
                ),
                $this->dynamicVariableReference
            );
        }
        return $dynamicVariables[$variableName];
    }
}
