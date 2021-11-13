<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

/**
 * Output types, as defined by the GraphQL spec:
 *
 * @see https://spec.graphql.org/draft/#sec-Input-and-Output-Types
 *
 * Included types:
 *
 * - ScalarType
 * - EnumType
 * - ObjectType
 * - InterfaceType
 */
interface OutputTypeResolverInterface extends TypeResolverInterface
{
}
