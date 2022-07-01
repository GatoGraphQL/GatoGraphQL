<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedback;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionErrorFeedbackItemProvider;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Response\OutputServiceInterface;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;
use stdClass;

class InputCoercingService implements InputCoercingServiceInterface
{
    use BasicServiceTrait;

    private ?OutputServiceInterface $outputService = null;

    final public function setOutputService(OutputServiceInterface $outputService): void
    {
        $this->outputService = $outputService;
    }
    final protected function getOutputService(): OutputServiceInterface
    {
        return $this->outputService ??= $this->instanceManager->getInstance(OutputServiceInterface::class);
    }

    /**
     * Support passing a single value where a list is expected:
     * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
     *
     * Defined in the GraphQL spec.
     *
     * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
     */
    public function maybeConvertInputValueASTFromSingleToList(
        WithValueInterface $inputValueAST,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
    ): WithValueInterface {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            $inputValueAST instanceof InputList
            || !$moduleConfiguration->convertInputValueFromSingleToList()
        ) {
            return $inputValueAST;
        }
        if ($inputIsArrayOfArraysType) {
            return new InputList(
                [
                    new InputList(
                        [
                            $inputValueAST,
                        ],
                        $inputValueAST->getLocation()
                    ),
                ],
                $inputValueAST->getLocation()
            );
        }
        if ($inputIsArrayType) {
            return new InputList(
                [
                    $inputValueAST,
                ],
                $inputValueAST->getLocation()
            );
        }
        return $inputValueAST;
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
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
    ): mixed {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            is_array($inputValue)
            || !$moduleConfiguration->convertInputValueFromSingleToList()
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
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): void {
        if (
            !$inputIsArrayType
            && is_array($inputValue)
        ) {
            $schemaInputValidationFeedbackStore->addError(
                new SchemaInputValidationFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E8,
                        [
                            $inputName,
                            json_encode($inputValue),
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $inputTypeResolver
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
            $schemaInputValidationFeedbackStore->addError(
                new SchemaInputValidationFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E9,
                        [
                            $inputName,
                            $inputValueAsString,
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $inputTypeResolver
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
            $schemaInputValidationFeedbackStore->addError(
                new SchemaInputValidationFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E10,
                        [
                            $inputName,
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $inputTypeResolver
                ),
            );
            return;
        }
        if (
            $inputIsArrayType
            && !$inputIsArrayOfArraysType
            && array_filter(
                $inputValue,
                fn ($arrayItem) => is_array($arrayItem)
            )
        ) {
            $schemaInputValidationFeedbackStore->addError(
                new SchemaInputValidationFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E11,
                        [
                            $inputName,
                            json_encode($inputValue),
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $inputTypeResolver
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
                fn ($arrayItem) => !is_array($arrayItem) && $arrayItem !== null
            )
        ) {
            $schemaInputValidationFeedbackStore->addError(
                new SchemaInputValidationFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E12,
                        [
                            $inputName,
                            json_encode($inputValue),
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $inputTypeResolver
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
            $schemaInputValidationFeedbackStore->addError(
                new SchemaInputValidationFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E13,
                        [
                            $inputName,
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $inputTypeResolver
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
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): mixed {
        if ($inputValue === null) {
            return $inputValue;
        }
        if ($inputIsArrayOfArraysType) {
            /** @var array $inputValue */
            // If the value is an array of arrays, then cast each subelement to the item type
            return array_map(
                // If it contains a null value, return it as is
                fn (?array $arrayArgValueElem) => $arrayArgValueElem === null ? null : array_map(
                    fn (mixed $arrayOfArraysArgValueElem) => $arrayOfArraysArgValueElem === null ? null : $inputTypeResolver->coerceValue($arrayOfArraysArgValueElem, $schemaInputValidationFeedbackStore),
                    $arrayArgValueElem
                ),
                $inputValue
            );
        }
        if ($inputIsArrayType) {
            /** @var array $inputValue */
            // If the value is an array, then cast each element to the item type
            return array_map(
                fn (mixed $arrayArgValueElem) => $arrayArgValueElem === null ? null : $inputTypeResolver->coerceValue($arrayArgValueElem, $schemaInputValidationFeedbackStore),
                $inputValue
            );
        }
        // Otherwise, simply cast the given value directly
        return $inputTypeResolver->coerceValue($inputValue, $schemaInputValidationFeedbackStore);
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
        if ($inputValue === null) {
            return [];
        }
        $inputValueDeprecations = [];
        if ($inputIsArrayOfArraysType) {
            // Execute against an array of arrays of values
            foreach ($inputValue as $arrayArgValueElem) {
                if ($arrayArgValueElem === null) {
                    continue;
                }
                foreach ($arrayArgValueElem as $arrayOfArraysArgValueElem) {
                    if ($arrayOfArraysArgValueElem === null) {
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
                if ($arrayArgValueElem === null) {
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
