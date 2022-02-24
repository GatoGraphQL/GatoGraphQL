<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use CastToType;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

class NumericScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Numeric';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Wildcard type representing any of the numeric types (Int or Float)', 'engine');
    }

    /**
     * Cast to either Int or Float
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
        $castInputValue = CastToType::_int($inputValue);
        if ($castInputValue !== null) {
            return (int) $castInputValue;
        }
        $castInputValue = CastToType::_float($inputValue);
        if ($castInputValue !== null) {
            return (float) $castInputValue;
        }
        $this->addDefaultErrorMessage($inputValue, $schemaInputValidationFeedbackStore);
        return null;
    }
}
