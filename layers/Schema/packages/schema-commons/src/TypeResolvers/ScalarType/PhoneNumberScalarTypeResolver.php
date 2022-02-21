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
class PhoneNumberScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'PhoneNumber';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Phone number scalar, such as +1-212-555-0149', 'component-model');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc3966#section-5.1';
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object|null {
        $this->validateIsString($inputValue, $schemaInputValidationFeedbackStore);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        if (\preg_match('/(\+{1}[0-9]{1,3}[0-9]{8,9})/', $inputValue) !== 1) {
            $schemaInputValidationFeedbackStore->addError(
                new SchemaInputValidationFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E3,
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
