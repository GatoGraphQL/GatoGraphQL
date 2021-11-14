<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\ErrorHandling\Error;
use stdClass;

/**
 * Tagged InputObject Type, as proposed for the GraphQL spec:
 *
 * @see https://github.com/graphql/graphql-spec/pull/733
 */
abstract class AbstractTaggedInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    /**
     * Validate that there is exactly one input set
     */
    protected function coerceInputObjectValue(stdClass $inputValue): stdClass|Error
    {
        $inputValueSize = count((array)$inputValue);
        if ($inputValueSize !== 1) {
            return new Error(
                $this->getErrorCode(),
                sprintf(
                    $this->getTranslationAPI()->__('The tagged input object \'%s\' must receive exactly 1 input, but %s', 'component-model'),
                    $this->getMaybeNamespacedTypeName(),
                    $inputValueSize === 0 ?
                        $this->getTranslationAPI()->__('no input was provided', 'component-model')
                        : sprintf(
                            $this->getTranslationAPI()->__('\'%s\' inputs were provided (\'%s\')', 'component-model'),
                            $inputValueSize,
                            implode(
                                $this->getTranslationAPI()->__('\', \'', 'component-model'),
                                array_keys((array)$inputValue)
                            )
                        )
                )
            );
        }

        return parent::coerceInputObjectValue($inputValue);
    }
}
