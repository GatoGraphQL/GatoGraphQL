<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ScalarType;

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
        return $this->__('Custom scalar representing a JSON Object of unrestricted shape', 'component-model');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc7159';
    }

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if (!($inputValue instanceof stdClass)) {
            return $this->getError(
                sprintf(
                    $this->__('Cannot cast value \'%s\' to type \'%s\'', 'component-model'),
                    $inputValue,
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return $inputValue;
    }
}
