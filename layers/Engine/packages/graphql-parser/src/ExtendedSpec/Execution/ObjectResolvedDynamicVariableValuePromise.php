<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Exception\RuntimeVariableReferenceException;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\ObjectResolvedDynamicVariableReference;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;
use SplObjectStorage;

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
        /** @var FieldInterface|null */
        $engineIterationCurrentField = App::getState('engine-iteration-current-field');
        if ($engineIterationCurrentObjectID === null
            || $engineIterationCurrentField === null) {
            throw new ShouldNotHappenException(
                $this->__(
                    'The Engine Iteration\'s current objectID/Field have not been set, so the Promise cannot be resolved'
                )
            );
        }

        /**
         * @var array<string|int,SplObjectStorage<FieldInterface,array<string,mixed>>> Array of [objectID => SplObjectStorage<Field, [dynamicVariableName => value]>]
         */
        $objectResolvedDynamicVariables = App::getState('object-resolved-dynamic-variables');
        $dynamicVariableName = $this->objectResolvedDynamicVariableReference->getName();
        if (!isset($objectResolvedDynamicVariables[$engineIterationCurrentObjectID])
            || !$objectResolvedDynamicVariables[$engineIterationCurrentObjectID]->contains($engineIterationCurrentField)
            || !array_key_exists($dynamicVariableName, $objectResolvedDynamicVariables[$engineIterationCurrentObjectID][$engineIterationCurrentField])
        ) {
            // Variable is nowhere defined => Error
            throw new RuntimeVariableReferenceException(
                new FeedbackItemResolution(
                    GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                    GraphQLExtendedSpecErrorFeedbackItemProvider::E10,
                    [
                        $this->objectResolvedDynamicVariableReference->getName(),
                        $engineIterationCurrentField->asFieldOutputQueryString(),
                        $engineIterationCurrentObjectID,
                    ]
                ),
                $this->objectResolvedDynamicVariableReference
            );
        }
        
        return $objectResolvedDynamicVariables[$engineIterationCurrentObjectID][$engineIterationCurrentField][$dynamicVariableName];
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
