<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\Root\Services\StandaloneServiceTrait;
use PoP\GraphQLParser\Exception\RuntimeVariableReferenceException;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DocumentDynamicVariableReference;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;

class DocumentDynamicVariableValuePromise implements ValueResolutionPromiseInterface
{
    use StandaloneServiceTrait;

    public function __construct(
        public readonly DocumentDynamicVariableReference $dynamicVariableReference,
    ) {
    }

    /**
     * @throws RuntimeVariableReferenceException When accessing non-declared Dynamic Variables
     */
    public function resolveValue(): mixed
    {
        $dynamicVariables = App::getState('document-dynamic-variables');
        $variableName = $this->dynamicVariableReference->getName();
        if (!array_key_exists($variableName, $dynamicVariables)) {
            // Variable is nowhere defined => Error
            throw new RuntimeVariableReferenceException(
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

    /**
     * The field/directiveArgs containing the promise must be resolved:
     *
     * Only once during the Engine Iteration for all involved fields/objects
     */
    public function mustResolveOnObject(): bool
    {
        return false;
    }
}
