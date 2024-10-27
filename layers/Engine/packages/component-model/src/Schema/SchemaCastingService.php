<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\GenericFeedbackItemProvider;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;

class SchemaCastingService implements SchemaCastingServiceInterface
{
    use BasicServiceTrait;

    private ?DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
    private ?InputCoercingServiceInterface $inputCoercingService = null;

    final protected function getDangerouslyNonSpecificScalarTypeScalarTypeResolver(): DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        if ($this->dangerouslyNonSpecificScalarTypeScalarTypeResolver === null) {
            /** @var DangerouslyNonSpecificScalarTypeScalarTypeResolver */
            $dangerouslyNonSpecificScalarTypeScalarTypeResolver = $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
            $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
        }
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final protected function getInputCoercingService(): InputCoercingServiceInterface
    {
        if ($this->inputCoercingService === null) {
            /** @var InputCoercingServiceInterface */
            $inputCoercingService = $this->instanceManager->getInstance(InputCoercingServiceInterface::class);
            $this->inputCoercingService = $inputCoercingService;
        }
        return $this->inputCoercingService;
    }

    /**
     * @param array<string,mixed> $argumentKeyValues
     * @param array<string,array<string,mixed>> $argumentSchemaDefinition
     * @return array<string,mixed>
     */
    public function castArguments(
        array $argumentKeyValues,
        array $argumentSchemaDefinition,
        WithArgumentsInterface $withArgumentsAST,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array {
        $inputCoercingService = $this->getInputCoercingService();
        // Cast all argument values
        foreach ($argumentKeyValues as $argName => $argValue) {
            /**
             * If the arg doesn't exist, there's already a warning about it missing
             * in the schema (not an error, in that case it's already not added)
             */
            if (!array_key_exists($argName, $argumentSchemaDefinition)) {
                continue;
            }

            /** @var InputTypeResolverInterface */
            $fieldOrDirectiveArgTypeResolver = $argumentSchemaDefinition[$argName][SchemaDefinition::TYPE_RESOLVER];

            /**
             * Execute the validation, checking that the WrappingType is respected.
             * Eg: `["hello"]` must be `[String]`, can't be `[[String]]` or `String`.
             *
             * Coerce the value to the appropriate type.
             * Eg: from string to boolean.
             **/
            $fieldOrDirectiveArgIsNonNullable = $argumentSchemaDefinition[$argName][SchemaDefinition::NON_NULLABLE] ?? false;
            $fieldOrDirectiveArgIsArrayType = $argumentSchemaDefinition[$argName][SchemaDefinition::IS_ARRAY] ?? false;
            $fieldOrDirectiveArgIsNonNullArrayItemsType = $argumentSchemaDefinition[$argName][SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false;
            $fieldOrDirectiveArgIsArrayOfArraysType = $argumentSchemaDefinition[$argName][SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false;
            $fieldOrDirectiveArgIsNonNullArrayOfArraysItemsType = $argumentSchemaDefinition[$argName][SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false;

            /**
             * `DangerouslyNonSpecificScalar` is a special scalar type which is not coerced or validated.
             * In particular, it does not need to validate if it is an array or not,
             * as according to the applied WrappingType.
             *
             * This is to enable it to have an array as value, which is not
             * allowed by GraphQL unless the array is explicitly defined.
             *
             * For instance, type `DangerouslyNonSpecificScalar` could have values
             * `"hello"` and `["hello"]`, but in GraphQL we must differentiate
             * these values by types `String` and `[String]`.
             */
            $isDangerouslyNonSpecificScalar = $fieldOrDirectiveArgTypeResolver === $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver();

            /**
             * DangerouslyNonScalar: Validate the cardinality, but only
             * if explicitly set to `true`. Otherwise change from `false`
             * to `null`, to indicate "do not validate".
             *
             *   - DangerouslyNonSpecificScalar does not need to validate anything => all null
             *   - [DangerouslyNonSpecificScalar] must certainly be an array, but it doesn't care
             *     inside if it's an array or not => $inputIsArrayType => true, $inputIsArrayOfArraysType => null
             *   - [[DangerouslyNonSpecificScalar]] must be array of arrays => $inputIsArrayType => true, $inputIsArrayOfArraysType => true
             */
            if ($isDangerouslyNonSpecificScalar) {
                if (!$fieldOrDirectiveArgIsNonNullable) {
                    $fieldOrDirectiveArgIsNonNullable = null;
                }
                if (!$fieldOrDirectiveArgIsArrayType) {
                    $fieldOrDirectiveArgIsArrayType = null;
                }
                if (!$fieldOrDirectiveArgIsArrayOfArraysType) {
                    $fieldOrDirectiveArgIsArrayOfArraysType = null;
                }
            } else {
                /**
                 * Modifying the AST and not directly its value, because
                 * a VariableReference may be converted to InputList([VariableReference]),
                 * so the underlying AST holding the value must also change.
                 *
                 * Support passing a single value where a list is expected:
                 * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
                 *
                 * Defined in the GraphQL spec.
                 *
                 * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
                 */
                $argValue = $inputCoercingService->maybeConvertInputValueFromSingleToList(
                    $argValue,
                    $fieldOrDirectiveArgIsNonNullable,
                    $fieldOrDirectiveArgIsArrayType,
                    $fieldOrDirectiveArgIsArrayOfArraysType,
                );
            }

            // Cast (or "coerce" in GraphQL terms) the value
            if ($withArgumentsAST->hasArgument($argName)) {
                /** @var Argument */
                $argument = $withArgumentsAST->getArgument($argName);
                $astNode = $argument->getValueAST();
            } else {
                $astNode = $withArgumentsAST;
            }

            // Validate that the expected array/non-array input is provided
            $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
            $inputCoercingService->validateInputArrayModifiers(
                $fieldOrDirectiveArgTypeResolver,
                $argValue,
                $argName,
                $fieldOrDirectiveArgIsNonNullable,
                $fieldOrDirectiveArgIsArrayType,
                $fieldOrDirectiveArgIsNonNullArrayItemsType,
                $fieldOrDirectiveArgIsArrayOfArraysType,
                $fieldOrDirectiveArgIsNonNullArrayOfArraysItemsType,
                $astNode,
                $objectTypeFieldResolutionFeedbackStore,
            );
            if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
                continue;
            }

            /**
             * DangerouslyNonScalar: just validate the cardinality,
             * no need to coerce the value
             */
            if ($isDangerouslyNonSpecificScalar) {
                continue;
            }

            /**
             * Cast (or "coerce" in GraphQL terms) the value
             *
             * @var bool $fieldOrDirectiveArgIsNonNullable
             * @var bool $fieldOrDirectiveArgIsArrayType
             * @var bool $fieldOrDirectiveArgIsArrayOfArraysType
             */
            $coercedArgValue = $inputCoercingService->coerceInputValue(
                $fieldOrDirectiveArgTypeResolver,
                $argValue,
                $argName,
                $fieldOrDirectiveArgIsNonNullable,
                $fieldOrDirectiveArgIsArrayType,
                $fieldOrDirectiveArgIsArrayOfArraysType,
                $astNode,
                $objectTypeFieldResolutionFeedbackStore,
            );
            if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
                continue;
            }

            /**
             * No errors, re-assign the coerced value to the data
             */
            $argumentKeyValues[$argName] = $coercedArgValue;

            /**
             * Obtain the deprecations
             */
            if ($fieldOrDirectiveArgTypeResolver instanceof DeprecatableInputTypeResolverInterface) {
                $deprecationMessages = $inputCoercingService->getInputValueDeprecationMessages(
                    $fieldOrDirectiveArgTypeResolver,
                    $coercedArgValue,
                    $fieldOrDirectiveArgIsArrayType,
                    $fieldOrDirectiveArgIsArrayOfArraysType,
                );
                foreach ($deprecationMessages as $deprecationMessage) {
                    $objectTypeFieldResolutionFeedbackStore->addDeprecation(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                GenericFeedbackItemProvider::class,
                                GenericFeedbackItemProvider::D1,
                                [
                                    $deprecationMessage,
                                ]
                            ),
                            $astNode,
                        )
                    );
                }
            }
        }
        return $argumentKeyValues;
    }
}
