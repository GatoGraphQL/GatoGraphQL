<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;

interface InputCoercingServiceInterface
{
    /**
     * Support passing a single value where a list is expected:
     * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
     *
     * Defined in the GraphQL spec.
     *
     * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
     *
     * @return WithValueInterface The provided value as is, converted to array, or converted to array of arrays
     */
    public function maybeConvertInputValueASTFromSingleToList(
        WithValueInterface $inputValueAST,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
    ): WithValueInterface;

    /**
     * Support passing a single value where a list is expected:
     * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
     *
     * Defined in the GraphQL spec.
     *
     * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
     */
    public function maybeConvertInputValueFromSingleToList(
        mixed $inputValue,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
    ): mixed;

    /**
     * Validate that the expected array/non-array input is provided,
     * checking that the WrappingType is respected.
     *
     * Eg: `["hello"]` must be `[String]`, can't be `[[String]]` or `String`.
     */
    public function validateInputArrayModifiers(
        InputTypeResolverInterface $inputTypeResolver,
        mixed $inputValue,
        string $inputName,
        bool $inputIsArrayType,
        bool $inputIsNonNullArrayItemsType,
        bool $inputIsArrayOfArraysType,
        bool $inputIsNonNullArrayOfArraysItemsType,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;

    /**
     * Coerce the input value, corresponding to the array type
     * defined by the modifiers.
     */
    public function coerceInputValue(
        InputTypeResolverInterface $inputTypeResolver,
        mixed $inputValue,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed;

    /**
     * If applicable, get the deprecation messages for the input value
     *
     * @return string[]
     */
    public function getInputValueDeprecationMessages(
        DeprecatableInputTypeResolverInterface $inputTypeResolver,
        mixed $inputValue,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType
    ): array;
}
