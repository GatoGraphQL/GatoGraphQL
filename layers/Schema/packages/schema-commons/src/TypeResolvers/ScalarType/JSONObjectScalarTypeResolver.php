<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

/**
 * GraphQL Custom Scalar representing a JSON Object on the client-side,
 * handled via an stdClass object on the server-side
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class JSONObjectScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'JSONObject';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Custom Scalar representing a JSON Object of unrestricted shape', 'component-model');
    }

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|stdClass|Error
    {
        if (!($inputValue instanceof stdClass)) {
            return $this->getError(
                sprintf(
                    $this->translationAPI->__('Cannot cast value \'%s\' to type \'%s\'', 'component-model'),
                    $inputValue,
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return $inputValue;
    }
}
