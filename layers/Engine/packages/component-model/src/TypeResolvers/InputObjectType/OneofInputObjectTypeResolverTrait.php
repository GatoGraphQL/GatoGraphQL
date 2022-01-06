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
     * The oneof input can be used for different uses, such as:
     *
     *   1. Get a specific user: `{ user(by:{id: 1}) { name } }`
     *   2. Search users: `{ users(searchBy:{name: "leo"}) { id } }`
     *
     * In the first case, the input is mandatory
     * In the second case, it is not
     *
     * Because InputObjects with no value in the query are initialized as {} (via `new stdClass`),
     * then we must explicitly check if the oneof input requires the one value or not.
     */
    protected function isOneInputValueMandatory(): bool
    {
        return true;
    }

    /**
     * Validate that there is exactly one input set
     */
    protected function validateOneofInputObjectValue(stdClass $inputValue): ?Error
    {
        $inputValueSize = count((array)$inputValue);
        if ($inputValueSize > 1) {
            return new Error(
                $this->getErrorCode(),
                sprintf(
                    $this->getTranslationaAPI()->__('The oneof input object \'%s\' must receive exactly 1 input, but \'%s\' inputs were provided (\'%s\')', 'component-model'),
                    $this->getMaybeNamespacedTypeName(),
                    $inputValueSize,
                    implode(
                        $this->getTranslationaAPI()->__('\', \'', 'component-model'),
                        array_keys((array)$inputValue)
                    )
                )
            );
        }
        if ($inputValueSize === 0 && $this->isOneInputValueMandatory()) {
            return new Error(
                $this->getErrorCode(),
                sprintf(
                    $this->getTranslationaAPI()->__('No input value was provided to the oneof input object \'%s\'', 'component-model'),
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }

        return null;
    }

    /**
     * Do not initialize the OneofInputObject for the unprovided values,
     * otherwise its validation may show up in the errors
     */
    protected function initializeInputFieldInputObjectValue(): bool
    {
        return $this->isOneInputValueMandatory();
    }
}
