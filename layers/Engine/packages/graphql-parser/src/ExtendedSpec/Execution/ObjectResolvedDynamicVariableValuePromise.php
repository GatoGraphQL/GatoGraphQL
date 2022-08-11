<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Exception\RuntimeVariableReferenceException;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\ObjectResolvedDynamicVariableReference;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\Root\App;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;

class ObjectResolvedDynamicVariableValuePromise implements ValueResolutionPromiseInterface
{
    use StandaloneServiceTrait;

    public function __construct(
        public readonly ObjectResolvedDynamicVariableReference $objectResolvedDynamicVariableReference,
    ) {
    }

    /**
     * @throws RuntimeVariableReferenceException When accessing non-declared Dynamic Variables
     */
    public function resolveValue(): mixed
    {
        /**
         * Retrieve which is the current object ID and Field
         * for which to retrieve the dynamic variable for.
         *
         * If any of them is not provided, it's a development error.
         *
         * @var string|int|null
         */
        $engineIterationCurrentObjectID = App::getState('engine-iteration-current-object-id');
        if ($engineIterationCurrentObjectID === null) {
            throw new ShouldNotHappenException(
                $this->__(
                    'The Engine Iteration\'s currently resolved objectID has not been set, so the Promise cannot be resolved'
                )
            );
        }

        /**
         * @var array<string|int,array<string,mixed>> Array of [objectID => [dynamicVariableName => value]]
         */
        $objectResolvedDynamicVariables = App::getState('object-resolved-dynamic-variables');
        $dynamicVariableName = $this->objectResolvedDynamicVariableReference->getName();
        if (
            !isset($objectResolvedDynamicVariables[$engineIterationCurrentObjectID])
            || !array_key_exists($dynamicVariableName, $objectResolvedDynamicVariables[$engineIterationCurrentObjectID])
        ) {
            // Variable is nowhere defined => Error
            throw new RuntimeVariableReferenceException(
                new FeedbackItemResolution(
                    GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                    GraphQLExtendedSpecErrorFeedbackItemProvider::E10,
                    [
                        $this->objectResolvedDynamicVariableReference->getName(),
                        $engineIterationCurrentObjectID,
                    ]
                ),
                $this->objectResolvedDynamicVariableReference
            );
        }

        return $objectResolvedDynamicVariables[$engineIterationCurrentObjectID][$dynamicVariableName];
    }

    /**
     * The field/directiveArgs containing the promise must be resolved:
     *
     * Object by object
     */
    public function mustResolveOnObject(): bool
    {
        return true;
    }
}
