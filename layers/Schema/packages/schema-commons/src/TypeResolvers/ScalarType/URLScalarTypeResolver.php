<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class URLScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'URL';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('URL scalar, such as https://mysite.com/my-fabulous-page', 'component-model');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://url.spec.whatwg.org/#url-representation';
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object {
        $this->validateIsString($inputValue, $schemaInputValidationFeedbackStore);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        $this->validateFilterVar($inputValue, $schemaInputValidationFeedbackStore, \FILTER_VALIDATE_URL);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        return $inputValue;
    }
}
