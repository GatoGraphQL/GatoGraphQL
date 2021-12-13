<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
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

    /**
     * Only validate the single provided entry, ignoring potential
     * errors from the unprovided entries.
     *
     * @return array<string, InputTypeResolverInterface>
     */
    protected function getInputFieldNameTypeResolversToCoerce(stdClass $inputValue): array
    {
        $inputFieldNameTypeResolvers = parent::getInputFieldNameTypeResolversToCoerce($inputValue);
        if (count((array)$inputValue) !== 1) {
            return $inputFieldNameTypeResolvers;
        }
        $inputField = key((array)$inputValue);
        return [
            $inputField => $inputFieldNameTypeResolvers[$inputField],
        ];
    }
}
