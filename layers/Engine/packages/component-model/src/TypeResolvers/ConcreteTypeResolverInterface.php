<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

/**
 * A "concrete" type is different than "output" type, because
 * the output type includes the InterfaceType:
 *
 * @see https://spec.graphql.org/draft/#sec-Input-and-Output-Types
 *
 * Concrete types are those that can resolve their fields to an actual value:
 *
 * - ObjectType
 * - UnionType
 * - ScalarType
 * - EnumType
 *
 * The InterfaceType indicates of what type the field is, without being able to
 * resolve it, hence it's not Concrete.
 *
 * Currently there is no OutputTypeResolver because
 * there is no need for it.
 */
interface ConcreteTypeResolverInterface extends TypeResolverInterface
{
}
