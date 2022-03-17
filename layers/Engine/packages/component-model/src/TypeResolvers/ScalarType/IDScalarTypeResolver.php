<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedback;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionErrorFeedbackItemProvider;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use stdClass;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class IDScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'ID';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The ID scalar type represents a unique identifier.', 'component-model');
    }

    /**
     * From the GraphQL spec, for section "ID > Input Coercion":
     *
     *   When expected as an input type, any string (such as "4")
     *   or integer (such as 4 or -4) input value should be coerced to ID
     *   as appropriate for the ID formats a given GraphQL service expects.
     *   Any other input value, including float input values (such as 4.0),
     *   must raise a request error indicating an incorrect type.
     *
     * @see https://spec.graphql.org/draft/#sec-ID.Input-Coercion
     */
    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object|null {
        $separateSchemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
        $this->validateIsNotStdClass($inputValue, $schemaInputValidationFeedbackStore);
        $schemaInputValidationFeedbackStore->incorporate($separateSchemaInputValidationFeedbackStore);
        if ($separateSchemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        /**
         * Type ID in GraphQL spec: only String or Int allowed.
         *
         * @see https://spec.graphql.org/draft/#sec-ID.Input-Coercion
         */
        if (is_float($inputValue) || is_bool($inputValue)) {
            $schemaInputValidationFeedbackStore->addError(
                new SchemaInputValidationFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E17,
                        [
                            $this->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $this
                ),
            );
            return null;
        }
        return $inputValue;
    }
}
