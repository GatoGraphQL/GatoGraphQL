<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use CastToType;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class FloatScalarTypeResolver extends AbstractScalarTypeResolver
{
    use BuiltInScalarTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'Float';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The Float scalar type represents float numbers.', 'component-model');
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object|null {
        $separateSchemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
        $this->validateIsNotStdClass($inputValue, $separateSchemaInputValidationFeedbackStore);
        $schemaInputValidationFeedbackStore->incorporate($separateSchemaInputValidationFeedbackStore);
        if ($separateSchemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        $castInputValue = CastToType::_float($inputValue);
        if ($castInputValue === null) {
            $this->addDefaultError($inputValue, $schemaInputValidationFeedbackStore);
            return null;
        }
        return (float) $castInputValue;
    }
}
