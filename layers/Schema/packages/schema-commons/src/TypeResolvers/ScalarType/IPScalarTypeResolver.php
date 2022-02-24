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
class IPScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'IP';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('IP scalar, including both IPv4 (such as 192.168.0.1) and IPv6 (such as 2001:0db8:85a3:08d3:1319:8a2e:0370:7334)', 'component-model');
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

        $separateSchemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
        $this->validateFilterVar($inputValue, $separateSchemaInputValidationFeedbackStore, \FILTER_VALIDATE_IP);
        $schemaInputValidationFeedbackStore->incorporate($separateSchemaInputValidationFeedbackStore);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        return $inputValue;
    }
}
