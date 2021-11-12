<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use stdClass;

/**
 * Input types which can be deprecated:
 *
 * - EnumType
 */
interface DeprecatableInputTypeResolverInterface extends InputTypeResolverInterface
{
    /**
     * For input types that can be deprecated (i.e. EnumType),
     * obtain the deprecation messages for an input value.
     *
     * @param string|int|float|bool|stdClass $inputValue the (custom) scalar in any format: itself (eg: an object) or its representation (eg: as a string)
     * @return string[] The deprecation messages
     */
    public function getInputValueDeprecationMessages(string|int|float|bool|stdClass $inputValue): array;
}
