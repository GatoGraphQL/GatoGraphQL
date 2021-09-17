<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

/**
 * Concrete types are those that can resolve their fields to an actual value:
 * 
 * - ObjectType
 * - UnionType
 * - ScalarType
 * - EnumType
 * 
 * The InterfaceType indicates of what type the field is, without being able to
 * resolve it, hence it's not Concrete
 */
interface ConcreteTypeResolverInterface extends TypeResolverInterface
{
}
