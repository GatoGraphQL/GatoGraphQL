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
class URLAbsolutePathScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'URLAbsolutePath';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('URL Absolute Path scalar, such as "/my-fabulous-page" in URL "https://mysite.com/my-fabulous-page". The absolute path starts with "/", followed by the URL relative path', 'component-model');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://url.spec.whatwg.org/#path-absolute-url-string';
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object|null {
        $this->validateIsString($inputValue, $schemaInputValidationFeedbackStore);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        $this->validateFilterVar('http://www.example.com' . $inputValue, $schemaInputValidationFeedbackStore, \FILTER_VALIDATE_URL);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        return $inputValue;
    }
}
