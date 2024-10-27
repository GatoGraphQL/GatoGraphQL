<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionGraphQLSpecErrorFeedbackItemProvider;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Response\OutputServiceInterface;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\GraphQLParser\ExtendedSpec\Execution\ValueResolutionPromiseInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\App;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;
use stdClass;

class InputCoercingService implements InputCoercingServiceInterface
{
    use BasicServiceTrait;

    private ?OutputServiceInterface $outputService = null;

    final protected function getOutputService(): OutputServiceInterface
    {
        if ($this->outputService === null) {
            /** @var OutputServiceInterface */
            $outputService = $this->instanceManager->getInstance(OutputServiceInterface::class);
            $this->outputService = $outputService;
        }
        return $this->outputService;
    }

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
        bool $inputIsNonNullable,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
    ): mixed {
        /**
         * If it is a Promise then don't convert it, since its underlying
         * value may actually be an array, but we don't know it yet.
         */
        if ($inputValue instanceof ValueResolutionPromiseInterface) {
            return $inputValue;
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            is_array($inputValue)
            || !$moduleConfiguration->convertInputValueFromSingleToList()
        ) {
            return $inputValue;
        }
        if (
            $inputValue === null
            && !$inputIsNonNullable
        ) {
            return $inputValue;
        }
        if ($inputIsArrayOfArraysType) {
            return [[$inputValue]];
        }
        if ($inputIsArrayType) {
            return [$inputValue];
        }
        return $inputValue;
    }

    /**
     * Validate that the expected array/non-array input is provided,
     * checking that the WrappingType is respected.
     *
     * Nullable booleans can be `null` for the DangerouslyNonSpecificScalar,
     * so they can also validate their cardinality:
     *
     *   - DangerouslyNonSpecificScalar does not need to validate anything => all null
     *   - [DangerouslyNonSpecificScalar] must certainly be an array, but it doesn't care
     *     inside if it's an array or not => $inputIsArrayType => true, $inputIsArrayOfArraysType => null
     *   - [[DangerouslyNonSpecificScalar]] must be array of arrays => $inputIsArrayType => true, $inputIsArrayOfArraysType => true
     *
     * Eg: `["hello"]` must be `[String]`, can't be `[[String]]` or `String`.
     */
    public function validateInputArrayModifiers(
        InputTypeResolverInterface $inputTypeResolver,
        mixed $inputValue,
        string $inputName,
        ?bool $inputIsNonNullable,
        ?bool $inputIsArrayType,
        ?bool $inputIsNonNullArrayItemsType,
        ?bool $inputIsArrayOfArraysType,
        ?bool $inputIsNonNullArrayOfArraysItemsType,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /**
         * If the type is `DangerouslyNonSpecificScalar`, there's nothing
         * to validate
         */
        if ($inputIsArrayType === null && $inputIsArrayOfArraysType === null) {
            return;
        }

        /**
         * If the value is null and the input can be nullable, there's nothing
         * to validate
         */
        if ($inputIsNonNullable === false && $inputValue === null) {
            return;
        }

        /**
         * If it is a Promise then don't convert it, since its underlying
         * value may actually be an array, but we don't know it yet.
         */
        if ($inputValue instanceof ValueResolutionPromiseInterface) {
            return;
        }

        if (
            $inputIsArrayType === false
            && is_array($inputValue)
        ) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_8,
                        [
                            $inputName,
                            json_encode($inputValue),
                        ]
                    ),
                    $astNode,
                ),
            );
            return;
        }
        if (
            $inputIsArrayType
            && !is_array($inputValue)
        ) {
            $inputValueAsString = $inputValue instanceof stdClass
                ? $this->getOutputService()->jsonEncodeArrayOrStdClassValue($inputValue)
                : $inputValue;
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_9,
                        [
                            $inputName,
                            $inputValueAsString,
                        ]
                    ),
                    $astNode,
                ),
            );
            return;
        }
        if (
            $inputIsNonNullArrayItemsType
            && is_array($inputValue)
            && array_filter(
                $inputValue,
                fn ($arrayItem) => $arrayItem === null
            )
        ) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_10,
                        [
                            $inputName,
                        ]
                    ),
                    $astNode,
                ),
            );
            return;
        }
        if (
            $inputIsArrayType
            && $inputIsArrayOfArraysType === false
            && array_filter(
                $inputValue,
                fn ($arrayItem) => is_array($arrayItem)
            )
        ) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_11,
                        [
                            $inputName,
                            json_encode($inputValue),
                        ]
                    ),
                    $astNode,
                ),
            );
            return;
        }
        if (
            $inputIsArrayOfArraysType
            && is_array($inputValue)
            && array_filter(
                $inputValue,
                // `null` could be accepted as an array! (Validation against null comes next)
                fn ($arrayItem) => !is_array($arrayItem) && $arrayItem !== null && !($arrayItem instanceof ValueResolutionPromiseInterface)
            )
        ) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_12,
                        [
                            $inputName,
                            json_encode($inputValue),
                        ]
                    ),
                    $astNode,
                ),
            );
            return;
        }
        if (
            $inputIsNonNullArrayOfArraysItemsType
            && is_array($inputValue)
            && array_filter(
                $inputValue,
                fn (?array $arrayItem) => $arrayItem === null ? false : array_filter(
                    $arrayItem,
                    fn ($arrayItemItem) => $arrayItemItem === null
                ) !== [],
            )
        ) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_13,
                        [
                            $inputName,
                        ]
                    ),
                    $astNode,
                ),
            );
            return;
        }
    }

    /**
     * Coerce the input value, corresponding to the array type
     * defined by the modifiers.
     */
    public function coerceInputValue(
        InputTypeResolverInterface $inputTypeResolver,
        mixed $inputValue,
        string $inputName,
        bool $inputIsNonNullable,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /**
         * If it is a null, validate the input can be nullable
         */
        if ($inputValue === null && $inputIsNonNullable) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_4,
                        [
                            $inputName,
                            $inputTypeResolver->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $astNode,
                ),
            );
            return null;
        }

        /**
         * If it is a null, nothing to coerce.
         *
         * If it is a ValueResolutionPromiseInterface, then we don't have the
         * value yet, so it can't be coerced.
         *
         * This has the implications that ObjectFieldValueReference
         * are not coerced!!! The output must already be the same
         * type as the input, and there's no validation to enforce it.
         */
        if ($inputValue === null || $inputValue instanceof ValueResolutionPromiseInterface) {
            return $inputValue;
        }
        if ($inputIsArrayOfArraysType) {
            /** @var array<mixed[]> $inputValue */
            // If the value is an array of arrays, then cast each subelement to the item type
            return array_map(
                // If it contains a null value, return it as is
                fn (array|ValueResolutionPromiseInterface|null $arrayArgValueElem) => ($arrayArgValueElem === null || $arrayArgValueElem instanceof ValueResolutionPromiseInterface) ? $arrayArgValueElem : array_map(
                    fn (mixed $arrayOfArraysArgValueElem) => ($arrayOfArraysArgValueElem === null || $arrayOfArraysArgValueElem instanceof ValueResolutionPromiseInterface) ? $arrayOfArraysArgValueElem : $this->validateAndCoerceInputValue($inputTypeResolver, $arrayOfArraysArgValueElem, $inputName, $astNode, $objectTypeFieldResolutionFeedbackStore),
                    $arrayArgValueElem
                ),
                $inputValue
            );
        }
        if ($inputIsArrayType) {
            /** @var mixed[] $inputValue */
            // If the value is an array, then cast each element to the item type
            return array_map(
                fn (mixed $arrayArgValueElem) => ($arrayArgValueElem === null || $arrayArgValueElem instanceof ValueResolutionPromiseInterface) ? $arrayArgValueElem : $this->validateAndCoerceInputValue($inputTypeResolver, $arrayArgValueElem, $inputName, $astNode, $objectTypeFieldResolutionFeedbackStore),
                $inputValue
            );
        }
        // Otherwise, simply cast the given value directly
        return $this->validateAndCoerceInputValue($inputTypeResolver, $inputValue, $inputName, $astNode, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Handle error from passing WP_Post (or any object)
     * as GraphQL variable, which is possible when executing
     * a query via the Internal GraphQL Server, as variables
     * are passed as a PHP array.
     */
    protected function validateAndCoerceInputValue(
        InputTypeResolverInterface $inputTypeResolver,
        string|int|float|bool|object $inputValue,
        string $inputName,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        if (is_object($inputValue) && !($inputValue instanceof stdClass)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_20,
                        [
                            $inputName,
                            $inputTypeResolver->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $astNode,
                ),
            );
            return null;
        }
        return $inputTypeResolver->coerceValue(
            $inputValue,
            $astNode,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * If applicable, get the deprecation messages for the input value
     *
     * @return string[]
     */
    public function getInputValueDeprecationMessages(
        DeprecatableInputTypeResolverInterface $deprecatableInputTypeResolver,
        mixed $inputValue,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType
    ): array {
        if ($inputValue === null || $inputValue instanceof ValueResolutionPromiseInterface) {
            return [];
        }
        $inputValueDeprecations = [];
        if ($inputIsArrayOfArraysType) {
            // Execute against an array of arrays of values
            foreach ($inputValue as $arrayArgValueElem) {
                if ($arrayArgValueElem === null || $arrayArgValueElem instanceof ValueResolutionPromiseInterface) {
                    continue;
                }
                foreach ($arrayArgValueElem as $arrayOfArraysArgValueElem) {
                    if ($arrayOfArraysArgValueElem === null || $arrayOfArraysArgValueElem instanceof ValueResolutionPromiseInterface) {
                        continue;
                    }
                    $inputValueDeprecations = array_merge(
                        $inputValueDeprecations,
                        $deprecatableInputTypeResolver->getInputValueDeprecationMessages($arrayOfArraysArgValueElem)
                    );
                }
            }
        } elseif ($inputIsArrayType) {
            // Execute against an array of values
            foreach ($inputValue as $arrayArgValueElem) {
                if ($arrayArgValueElem === null || $arrayArgValueElem instanceof ValueResolutionPromiseInterface) {
                    continue;
                }
                $inputValueDeprecations = array_merge(
                    $inputValueDeprecations,
                    $deprecatableInputTypeResolver->getInputValueDeprecationMessages($arrayArgValueElem)
                );
            }
        } else {
            // Execute against the single value
            $inputValueDeprecations = $deprecatableInputTypeResolver->getInputValueDeprecationMessages($inputValue);
        }
        return array_unique($inputValueDeprecations);
    }
}
