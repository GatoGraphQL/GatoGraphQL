<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedback;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoPSchema\SchemaCommons\FeedbackItemProviders\InputValueCoercionErrorFeedbackItemProvider;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class UUIDScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'UUID';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('UUID (universally unique identifier) scalar, such as 25770975-0c3d-4ff0-ba27-a0f98fe9b052', 'component-model');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc4122';
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object|null {
        $separateSchemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
        $this->validateIsString($inputValue, $schemaInputValidationFeedbackStore);
        $schemaInputValidationFeedbackStore->incorporate($separateSchemaInputValidationFeedbackStore);
        if ($separateSchemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        if (\preg_match('/^{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}}?$/', $inputValue) !== 1) {
            $schemaInputValidationFeedbackStore->addError(
                new SchemaInputValidationFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E4,
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
