<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Error\Error;
use stdClass;

/**
 * Oneof InputObject Type, as proposed for the GraphQL spec:
 *
 * @see https://github.com/graphql/graphql-spec/pull/825
 */
abstract class AbstractOneofInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements OneofInputObjectTypeResolverInterface
{
    use OneofInputObjectTypeResolverTrait;

    /**
     * Validate that there is exactly one input set
     */
    protected function coerceInputObjectValue(stdClass $inputValue): stdClass|Error
    {
        if ($error = $this->validateOneofInputObjectValue($inputValue)) {
            return $error;
        }
        return parent::coerceInputObjectValue($inputValue);
    }
}
