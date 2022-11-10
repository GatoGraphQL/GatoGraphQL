<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\Exception\RuntimeVariableReferenceException;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\ObjectResolvedDynamicVariableReference;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
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
        $currentObjectID = App::getState('object-resolved-dynamic-variables-current-object-id');
        if ($currentObjectID === null) {
            /**
             * Throw a message for the user instead of ShouldNotHappenException,
             * as in certain situations the query could lead to this problem,
             * so it's not only during development.
             *
             * Eg: attempting to use it with @skip or @include:
             *
             * ```
             * {
             *   users {
             *     id
             *     slug
             *       @passOnwards(as: "userSlug")
             *       @applyField(
             *         name: "_strContains"
             *         arguments: { search: "-", in: $userSlug }
             *         passOnwardsAs: "isSpecialSlug"
             *       )
             *       @skip(if: $isSpecialSlug)
             *   }
             * }
             * ```
             */
            throw new RuntimeVariableReferenceException(
                new FeedbackItemResolution(
                    GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                    GraphQLExtendedSpecErrorFeedbackItemProvider::E16,
                    [
                        $this->objectResolvedDynamicVariableReference->getName(),
                    ]
                ),
                $this->objectResolvedDynamicVariableReference
            );
        }

        /** @var SplObjectStorage<FieldInterface,array<string|int,array<string,mixed>>> SplObjectStorage<Field, [objectID => [dynamicVariableName => value]]> */
        $objectResolvedDynamicVariables = App::getState('object-resolved-dynamic-variables');
        $dynamicVariableName = $this->objectResolvedDynamicVariableReference->getName();

        /**
         * First check if the value has been set for the specific field.
         * (This allows @forEach to export the iterated upon values.)
         */
        $currentField = App::getState('object-resolved-dynamic-variables-current-field');
        if (
            $currentField !== null
            && $objectResolvedDynamicVariables->contains($currentField)
            && isset($objectResolvedDynamicVariables[$currentField][$currentObjectID])
            && array_key_exists($dynamicVariableName, $objectResolvedDynamicVariables[$currentField][$currentObjectID])
        ) {
            return $objectResolvedDynamicVariables[$currentField][$currentObjectID][$dynamicVariableName];
        }

        /**
         * If the value was not set for the combination of objectID + Field,
         * only then check for the objectID alone. To simplify the structure,
         * this is stored under the "wildcard field"
         */
        $wildcardField = ASTNodesFactory::getWildcardField();
        if (
            $objectResolvedDynamicVariables->contains($wildcardField)
            && isset($objectResolvedDynamicVariables[$wildcardField][$currentObjectID])
            && array_key_exists($dynamicVariableName, $objectResolvedDynamicVariables[$wildcardField][$currentObjectID])
        ) {
            return $objectResolvedDynamicVariables[$wildcardField][$currentObjectID][$dynamicVariableName];
        }

        // Variable is nowhere defined => Error
        throw new RuntimeVariableReferenceException(
            new FeedbackItemResolution(
                GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                GraphQLExtendedSpecErrorFeedbackItemProvider::E10,
                [
                    $this->objectResolvedDynamicVariableReference->getName(),
                    $currentObjectID,
                ]
            ),
            $this->objectResolvedDynamicVariableReference
        );
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
