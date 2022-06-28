<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedback;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\FeedbackItemProviders\GenericFeedbackItemProvider;
use PoP\Root\Services\BasicServiceTrait;

class SchemaCastingService implements SchemaCastingServiceInterface
{
    use BasicServiceTrait;

    private ?DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
    private ?InputCoercingServiceInterface $inputCoercingService = null;

    final public function setDangerouslyNonSpecificScalarTypeScalarTypeResolver(DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver): void
    {
        $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final protected function getDangerouslyNonSpecificScalarTypeScalarTypeResolver(): DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
    }
    final public function setInputCoercingService(InputCoercingServiceInterface $inputCoercingService): void
    {
        $this->inputCoercingService = $inputCoercingService;
    }
    final protected function getInputCoercingService(): InputCoercingServiceInterface
    {
        return $this->inputCoercingService ??= $this->instanceManager->getInstance(InputCoercingServiceInterface::class);
    }

    /**
     * @param array<string,array<string,mixed>> $argumentSchemaDefinition
     */
    public function castArguments(
        WithArgumentsInterface $withArgumentsAST,
        array $argumentSchemaDefinition,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): void {
        // Cast all argument values
        foreach ($withArgumentsAST->getArguments() as $argument) {
            $argName = $argument->getName();

            /**
             * If the arg doesn't exist, there's already a warning about it missing
             * in the schema (not an error, in that case it's already not added)
             */
            if (!array_key_exists($argName, $argumentSchemaDefinition)) {
                continue;
            }

            $argValueAST = $argument->getValueAST();

            /** @var InputTypeResolverInterface */
            $fieldOrDirectiveArgTypeResolver = $argumentSchemaDefinition[$argName][SchemaDefinition::TYPE_RESOLVER];

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
            if ($fieldOrDirectiveArgTypeResolver === $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver()) {
                continue;
            }

            /**
             * Execute the validation, checking that the WrappingType is respected.
             * Eg: `["hello"]` must be `[String]`, can't be `[[String]]` or `String`.
             *
             * Coerce the value to the appropriate type.
             * Eg: from string to boolean.
             **/
            $fieldOrDirectiveArgIsArrayType = $argumentSchemaDefinition[$argName][SchemaDefinition::IS_ARRAY] ?? false;
            $fieldOrDirectiveArgIsNonNullArrayItemsType = $argumentSchemaDefinition[$argName][SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false;
            $fieldOrDirectiveArgIsArrayOfArraysType = $argumentSchemaDefinition[$argName][SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false;
            $fieldOrDirectiveArgIsNonNullArrayOfArraysItemsType = $argumentSchemaDefinition[$argName][SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false;

            /**
             * Support passing a single value where a list is expected:
             * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
             *
             * Defined in the GraphQL spec.
             *
             * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
             */
            $argValueAST = $this->getInputCoercingService()->maybeConvertInputValueFromSingleToList(
                $argValueAST,
                $fieldOrDirectiveArgIsArrayType,
                $fieldOrDirectiveArgIsArrayOfArraysType,
            );

            // Validate that the expected array/non-array input is provided
            $separateSchemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
            $this->getInputCoercingService()->validateInputArrayModifiers(
                $fieldOrDirectiveArgTypeResolver,
                $argValueAST,
                $argName,
                $fieldOrDirectiveArgIsArrayType,
                $fieldOrDirectiveArgIsNonNullArrayItemsType,
                $fieldOrDirectiveArgIsArrayOfArraysType,
                $fieldOrDirectiveArgIsNonNullArrayOfArraysItemsType,
                $separateSchemaInputValidationFeedbackStore,
            );
            $schemaInputValidationFeedbackStore->incorporate($separateSchemaInputValidationFeedbackStore);
            if ($separateSchemaInputValidationFeedbackStore->getErrors() !== []) {
                continue;
            }

            // Cast (or "coerce" in GraphQL terms) the value
            $separateSchemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
            $coercedArgValueAST = $this->getInputCoercingService()->coerceInputValue(
                $fieldOrDirectiveArgTypeResolver,
                $argValueAST,
                $fieldOrDirectiveArgIsArrayType,
                $fieldOrDirectiveArgIsArrayOfArraysType,
                $separateSchemaInputValidationFeedbackStore,
            );
            $schemaInputValidationFeedbackStore->incorporate($separateSchemaInputValidationFeedbackStore);
            if ($separateSchemaInputValidationFeedbackStore->getErrors() !== []) {
                continue;
            }

            // Obtain the deprecations
            if ($fieldOrDirectiveArgTypeResolver instanceof DeprecatableInputTypeResolverInterface) {
                $deprecationMessages = $this->getInputCoercingService()->getInputValueDeprecationMessages(
                    $fieldOrDirectiveArgTypeResolver,
                    $coercedArgValueAST,
                    $fieldOrDirectiveArgIsArrayType,
                    $fieldOrDirectiveArgIsArrayOfArraysType,
                );
                foreach ($deprecationMessages as $deprecationMessage) {
                    $schemaInputValidationFeedbackStore->addDeprecation(
                        new SchemaInputValidationFeedback(
                            new FeedbackItemResolution(
                                GenericFeedbackItemProvider::class,
                                GenericFeedbackItemProvider::D1,
                                [
                                    $deprecationMessage,
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $fieldOrDirectiveArgTypeResolver,
                        )
                    );
                }
            }

            // No errors, re-assign the coerced value to the Argument
            $argument->setValueAST($coercedArgValueAST);
        }
    }
}
