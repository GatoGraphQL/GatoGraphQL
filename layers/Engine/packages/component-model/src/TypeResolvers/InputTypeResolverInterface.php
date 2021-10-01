<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

/**
 * Input types are those that can be provided inputs via field arguments:
 *
 * - ScalarType
 * - EnumType
 * - InputObjectType
 */
interface InputTypeResolverInterface extends TypeResolverInterface
{
}
