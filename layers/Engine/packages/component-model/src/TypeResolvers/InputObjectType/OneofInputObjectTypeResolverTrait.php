<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Error\Error;
use PoP\Translation\TranslationAPIInterface;
use stdClass;

trait OneofInputObjectTypeResolverTrait
{
    abstract protected function getErrorCode(): string;
    abstract protected function getTranslationAPI(): TranslationAPIInterface;
    abstract public function getMaybeNamespacedTypeName(): string;

    /**
     * Validate that there is exactly one input set
     */
    protected function validateOneofInputObjectValue(stdClass $inputValue): ?Error
    {
        $inputValueSize = count((array)$inputValue);
        if ($inputValueSize !== 1) {
            return new Error(
                $this->getErrorCode(),
                sprintf(
                    $this->getTranslationAPI()->__('The oneof input object \'%s\' must receive exactly 1 input, but %s', 'component-model'),
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

        return null;
    }
}
