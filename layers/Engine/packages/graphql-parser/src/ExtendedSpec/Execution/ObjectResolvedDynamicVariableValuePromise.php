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
         * Retrieve which is the current object ID for which
         * to retrieve the dynamic variable for.
         *
         * If not provided, it's a development error.
         */
        if (!App::hasState('engine-iteration-current-object-id')) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__(
                        'AppState \'%s\' has not been set, so the Promise cannot be resolved'
                    ),
                    'engine-iteration-current-object-id'
                )
            );
        }
        /** @var string|int */
        $engineIterationCurrentObjectID = App::getState('engine-iteration-current-object-id');

        /**
         * @var array<string|int,array<string,mixed>> Array of [objectID => [dynamicVariableName => value]]
         */
        $objectResolvedDynamicVariables = App::getState('object-resolved-dynamic-variables');
        $dynamicVariableName = $this->objectResolvedDynamicVariableReference->getName();
        if (!array_key_exists($engineIterationCurrentObjectID, $objectResolvedDynamicVariables)
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
